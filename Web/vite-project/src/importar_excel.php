<?php
// Asegúrate de que esta ruta sea correcta para acceder al autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// --- CONFIGURACIÓ DE RUTES I SERVEIS ---
$UPLOAD_DIR = __DIR__ . '/uploads/';
$DATA_DIR = __DIR__ . '/../public/data/';
$OUTPUT_FILE = $DATA_DIR . 'datos.json'; // Usando datos.json según tu docker-compose
$TARGET_API_URL_LOCAL = 'http://localhost:3000/productes';

// --- ESTAT I MISSATGES ---
$message = "";
$errors = [];
$imported_count = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excel_file"])) {
    $file = $_FILES["excel_file"];

    // 1. Validació inicial de la pujada
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Error al pujar l'arxiu. Codi: " . $file["error"];
    } else {
        $allowed_extensions = ['xlsx', 'xls', 'csv'];
        $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $errors[] = "Extensió d'arxiu no permesa. Només s'accepten: " . implode(', ', $allowed_extensions);
        }

        if (empty($errors)) {
            // 2. Moure i obtenir ruta de l'arxiu
            $unique_filename = uniqid('import_') . '.' . $file_extension;
            $upload_path = $UPLOAD_DIR . $unique_filename;

            // Crear directoris si no existeixen
            if (!is_dir($UPLOAD_DIR)) {
                if (!mkdir($UPLOAD_DIR, 0777, true)) {
                    $errors[] = "No es pot crear el directori d'uploads. Comprova permisos.";
                }
            }
            if (!is_dir($DATA_DIR)) {
                if (!mkdir($DATA_DIR, 0777, true)) {
                    $errors[] = "No es pot crear el directori de dades. Comprova permisos.";
                }
            }

            if (empty($errors) && !move_uploaded_file($file["tmp_name"], $upload_path)) {
                $errors[] = "Error al moure l'arxiu pujat. Comprova permisos a /src/uploads.";
            } else if (empty($errors)) {

                // 3. Llegir i processar Excel amb PhpSpreadsheet
                $data = process_excel($upload_path, $errors, $imported_count);

                if (!empty($data)) {
                    // 4. Generar i guardar JSON (PRESERVANT DADES EXISTENTS)
                    if (save_json($data, $OUTPUT_FILE)) {
                        $message = "✅ Importació finalitzada amb èxit! Productes processats: **{$imported_count}**";
                    } else {
                        $errors[] = "Error al guardar l'arxiu JSON. Comprova els permisos de la carpeta 'public/data'.";
                    }
                } else {
                    // Este error se produce si process_excel devuelve un array vacío (por ejemplo, solo cabeceras)
                    if (empty($errors)) {
                        $errors[] = "No s'han trobat dades vàlides per a la importació.";
                    }
                }

                // Neteja: Eliminar arxiu pujat
                if (file_exists($upload_path)) {
                    unlink($upload_path);
                }
            }
        }
    }
}

/**
 * Funció que llegeix l'Excel, valida dades i construeix l'array de productes.
 */
function process_excel(string $filePath, array &$errors, int &$imported_count): array
{
    $products = [];

    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // 1. LECTURA I NORMALITZACIÓ DE CAPÇALERES (Insensible a majúscules/minúscules)
        $header = [];
        $normalized_header = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $cell_value = trim($cell->getValue());
                $header[] = $cell_value;
                $normalized_header[] = strtolower($cell_value);
            }
        }

        // Mapeig de columnes que esperem (tant el nom original com el camp JSON)
        $expected_cols = [
            'Nom' => 'nom',
            'Descripcio' => 'descripcio',
            'Preu' => 'preu',
            'Estoc' => 'estoc',
            'SKU' => 'sku',
            'IMG' => 'img',
            'Categoria' => 'categoria',
            'Destacat' => 'destacat'
        ];
        $col_map = [];
        $missing_mandatory = [];

        // 2. BUSCAR ÍNDEXS UTILITZANT NORMALITZACIÓ
        foreach ($expected_cols as $original_name => $normalized_name) {
            $index = array_search($normalized_name, $normalized_header);
            $col_map[$original_name] = $index;

            // Comprovació estricta de tots els camps que necessites
            if ($index === false) {
                $missing_mandatory[] = $original_name;
            }
        }

        // Validar si falta alguna columna
        if (!empty($missing_mandatory)) {
            $errors[] = "L'arxiu Excel no té totes les columnes obligatòries a la primera fila. Faltants: " . implode(', ', $missing_mandatory);
            return [];
        }

        // 3. Recórrer les files de dades (començant per la fila 2)
        for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
            $rowData = [];
            foreach ($col_map as $key => $colIndex) {
                if ($colIndex !== false) {
                    // Obtener la coordenada de celda (ej: A2, B2)
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $coordinate = $colLetter . $rowNum;
                    $cell = $sheet->getCell($coordinate);

                    $cellValue = $cell->getFormattedValue();
                    $rowData[$key] = trim($cellValue);
                }
            }

            // Validació de dades
            $nom = $rowData['Nom'] ?? '';
            $preu_str = str_replace(',', '.', $rowData['Preu'] ?? '0');
            $preu = floatval($preu_str);
            $estoc_raw = $rowData['Estoc'] ?? '0';
            $estoc = intval($estoc_raw);

            // Regles de validació
            if (empty($nom)) {
                $errors[] = "Fila $rowNum ignorada: El camp 'Nom' és obligatori.";
                continue;
            }
            if (!is_numeric($preu_str) || $preu <= 0) {
                $errors[] = "Fila $rowNum ignorada: El 'Preu' no és un valor numèric vàlid (trobat: {$rowData['Preu']}).";
                continue;
            }
            // Comprobar que el valor crudo de Estoc sea numérico
            if (!is_numeric($estoc_raw) || $estoc < 0) {
                $errors[] = "Fila $rowNum ignorada: L' 'Estoc' no és un valor numèric vàlid (trobat: {$estoc_raw}).";
                continue;
            }

            // --- CORRECCIÓN CLAVE AQUÍ ---
            $destacat_val = strtolower($rowData['Destacat'] ?? 'false');
            $is_destacado = ($destacat_val === 'true' || $destacat_val === '1' || $destacat_val === 'v'); // Acepta 'true', '1' o 'v' (verdadero/veritat)

            // Estructura final del producte (Coincideix amb el teu JSON original)
            $products[] = [
                "id" => $rowData['SKU'] ?? ($rowNum - 1),
                "sku" => $rowData['SKU'] ?? 'N/A',
                "nombre" => $nom,
                "descripcion" => $rowData['Descripcio'] ?? 'Sense descripció.',
                "precio" => round($preu, 2),
                "categoria" => $rowData['Categoria'] ?? 'General',
                "destacado" => $is_destacado, // <-- CORRECCIÓN APLICADA
                "imagen" => $rowData['IMG'] ?? 'img/default.jpg',
                "estoc" => $estoc
            ];
            $imported_count++;
        }
    } catch (Exception $e) {
        $errors[] = "Error en la lectura de l'Excel: " . $e->getMessage();
    } catch (\Throwable $e) {
        $errors[] = "Error inesperat: " . $e->getMessage();
    }

    return $products;
}

/**
 * Funció CORREGIDA: Preserva les dades existents (com "usuarios") i només 
 * actualitza la col·lecció "productes" abans de guardar.
 */
function save_json(array $products, string $filePath): bool
{
    // 1. Inicializar array para datos existentes
    $existing_data = [];

    // 2. Leer contenido existente (si existe y si es válido)
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        // Usar 'true' para obtener un array asociativo
        $existing_data = json_decode($content, true) ?: [];
    }

    // 3. Actualizar SOLAMENTE la colección 'productes' con los nuevos datos
    $existing_data["productes"] = $products;

    // 4. Re-codificar el JSON completo
    // JSON_UNESCAPED_UNICODE ayuda con caracteres especiales
    $json_content = json_encode($existing_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // 5. Guardar el JSON
    return file_put_contents($filePath, $json_content) !== false;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>C1 | Importació d'Excel</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        .import-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: var(--color-gray-dark);
            padding: 2rem;
            border-radius: var(--radius);
        }

        .import-message-success {
            background-color: #104020;
            color: #b4f1c1;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .import-message-error {
            background-color: #661010;
            color: #ffcccc;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="import-container">
        <h1 style="color: var(--color-gold); font-family: var(--font-serif); font-size: 2rem; margin-bottom: 1.5rem;">Importar Catàleg (Excel a JSON Server)</h1>

        <?php if (!empty($message)): ?>
            <div class="import-message-success">
                <?= htmlspecialchars($message) ?>
                <p style="margin-top: 0.5rem;">Pots comprovar el JSON generat a: <a href="<?= $TARGET_API_URL_LOCAL ?>" target="_blank" style="color: var(--color-gold-light);">http://localhost:3000/productes</a></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="import-message-error">
                <h3 style="margin-bottom: 0.5rem; font-size: 1.125rem;">Errors i Ignorats (<?= count($errors) ?>):</h3>
                <ul style="list-style: disc; margin-left: 1.5rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1rem;">
            <p style="color: var(--color-gray-lighter); font-size: 0.875rem;">Requisits de Columnes a la primera fila de l'Excel: **Nom**, **Descripcio**, **Preu**, **Estoc**, **SKU**, **IMG**, **Categoria**, **Destacat**.</p>
            <label for="excel_file" style="font-weight: 500;">Selecciona el fitxer Excel (.xlsx, .xls, .csv):</label>
            <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required style="
                padding: 0.75rem;
                border-radius: 0.375rem;
                border: 1px solid var(--color-gray-light);
                background-color: var(--color-gray);
                color: var(--color-white);
            ">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                Importar i Generar JSON
            </button>
        </form>
    </div>
</body>

</html>