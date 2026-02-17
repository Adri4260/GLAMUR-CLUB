<?php
// Web/vite-project/src/importar_excel.php
// Script modificado para manejar la importación de datos de Productos O de Valoraciones

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// --- CONFIGURACIÓ DE RUTES I SERVEIS ---
$UPLOAD_DIR = __DIR__ . '/uploads/';
$DATA_DIR = __DIR__ . '/../public/data/';
$OUTPUT_FILE = $DATA_DIR . 'datos.json';
$TARGET_API_URL_LOCAL = 'http://localhost:3000/productes'; // Endpoint por defecto, puede variar

// --- ESTAT I MISSATGES ---
$message = "";
$errors = [];
$imported_count = 0;
$collection_name = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excel_file"])) {
    $file = $_FILES["excel_file"];

    // 1. Validació inicial de la pujada (Cuerpo principal)
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Error al pujar l'arxiu. Codi: " . $file["error"];
    } else {
        // ... (Validaciones de extensión y movimiento de archivo permanecen igual) ...
        $allowed_extensions = ['xlsx', 'xls', 'csv'];
        $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "Extensió d'arxiu no permesa. Només s'accepten: " . implode(', ', $allowed_extensions);
        }

        if (empty($errors)) {
            $unique_filename = uniqid('import_') . '.' . $file_extension;
            $upload_path = $UPLOAD_DIR . $unique_filename;

            if (!is_dir($UPLOAD_DIR) && !mkdir($UPLOAD_DIR, 0777, true)) {
                $errors[] = "No es pot crear el directori d'uploads. Comprova permisos.";
            }
            if (!is_dir($DATA_DIR) && !mkdir($DATA_DIR, 0777, true)) {
                $errors[] = "No es pot crear el directori de dades. Comprova permisos.";
            }

            if (empty($errors) && !move_uploaded_file($file["tmp_name"], $upload_path)) {
                $errors[] = "Error al moure l'arxiu pujat. Comprova permisos a /src/uploads.";
            } else if (empty($errors)) {

                // 3. LECTURA Y PROCESAMIENTO (AHORA CON LÓGICA DE COLECCIÓN)
                $result = process_import_data($upload_path, $errors, $imported_count);
                $data = $result['data'] ?? [];
                $collection_name = $result['collection_name'] ?? '';

                if (!empty($data) && $collection_name) {
                    // 4. Generar y guardar JSON, actualizando la colección correcta
                    if (save_json($data, $OUTPUT_FILE, $collection_name)) {
                        $message = "✅ Importació **{$collection_name}** finalitzada amb èxit! Registres processats: **{$imported_count}**";
                        $TARGET_API_URL_LOCAL = 'http://localhost:3000/' . $collection_name; // Actualizar URL de verificación
                    } else {
                        $errors[] = "Error al guardar l'arxiu JSON. Comprova els permisos de la carpeta 'public/data'.";
                    }
                } else {
                    if (empty($errors)) {
                        $errors[] = "No s'han trobat dades vàlides o la capçalera no coincideix amb cap format d'importació esperat.";
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
 * Funció principal que llegeix l'Excel i determina la col·lecció.
 */
function process_import_data(string $filePath, array &$errors, int &$imported_count): array
{
    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // 1. LECTURA DE CAPÇALERES
        $header = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $header[] = strtolower(trim($cell->getValue()));
            }
        }

        // 2. DETECTAR TIPUS DE COL·LECCIÓ
        $product_headers = ['nom', 'descripcio', 'preu', 'estoc', 'sku', 'img', 'categoria', 'destacat'];
        $valoracion_headers = ['id_valoracion', 'usuario_id', 'producto_id', 'comentario', 'puntuacion', 'me_gusta', 'fecha'];

        $is_product_import = !array_diff($product_headers, $header) && count($product_headers) === count($header);
        $is_valoracion_import = !array_diff($valoracion_headers, $header) && count($valoracion_headers) === count($header);

        if ($is_product_import) {
            return ['data' => process_products($sheet, $header, $errors, $imported_count), 'collection_name' => 'productes'];
        } elseif ($is_valoracion_import) {
            return ['data' => process_valoracions($sheet, $header, $errors, $imported_count), 'collection_name' => 'valoracions'];
        } else {
            $errors[] = "Capçaleres no reconegudes. Assegura't de pujar productes (8 columnes) o valoracions (7 columnes).";
            return ['data' => [], 'collection_name' => ''];
        }
    } catch (Exception $e) {
        $errors[] = "Error en la lectura de l'Excel: " . $e->getMessage();
    } catch (\Throwable $e) {
        $errors[] = "Error inesperat: " . $e->getMessage();
    }
    return ['data' => [], 'collection_name' => ''];
}

/**
 * Funció que llegeix dades de PRODUCTES.
 */
function process_products($sheet, array $normalized_header, array &$errors, int &$imported_count): array
{
    $products = [];
    $highestRow = $sheet->getHighestRow();

    $expected_cols = [
        'Nom' => 'nom',
        'Descripcio' => 'descripcio',
        'Preu' => 'preu',
        'Estoc' => 'estoc',
        'SKU' => 'sku',
        'IMG' => 'imagen',
        'Categoria' => 'categoria',
        'Destacat' => 'destacado'
    ];
    $col_map = [];

    foreach ($expected_cols as $original_name => $normalized_name) {
        $col_map[$original_name] = array_search(strtolower($original_name), $normalized_header);
    }

    // 3. Recórrer les files de dades (començant per la fila 2)
    for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
        $rowData = [];
        foreach ($col_map as $key => $colIndex) {
            if ($colIndex !== false) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $coordinate = $colLetter . $rowNum;
                $cell = $sheet->getCell($coordinate);
                $rowData[$key] = trim($cell->getFormattedValue());
            }
        }

        // Validació de dades i conversió
        $nom = $rowData['Nom'] ?? '';
        $preu_str = str_replace(',', '.', $rowData['Preu'] ?? '0');
        $preu = floatval($preu_str);
        $estoc_raw = $rowData['Estoc'] ?? '0';
        $estoc = intval($estoc_raw);
        $destacat_val = strtolower($rowData['Destacat'] ?? 'false');
        $is_destacado = ($destacat_val === 'true' || $destacat_val === '1' || $destacat_val === 'v');

        if (empty($nom) || $preu <= 0 || $estoc < 0) {
            $errors[] = "Fila $rowNum ignorada: Dades de producte invàlides.";
            continue;
        }

        // Estructura final del producte (SIN el campo 'comentarios' anidado)
        $products[] = [
            "id" => $rowData['SKU'] ?? ($rowNum - 1),
            "sku" => $rowData['SKU'] ?? 'N/A',
            "nombre" => $nom,
            "descripcion" => $rowData['Descripcio'] ?? 'Sense descripció.',
            "precio" => round($preu, 2),
            "categoria" => $rowData['Categoria'] ?? 'General',
            "destacado" => $is_destacado,
            "imagen" => $rowData['IMG'] ?? 'img/default.jpg',
            "estoc" => $estoc
        ];
        $imported_count++;
    }
    return $products;
}

/**
 * Funció que llegeix dades de VALORACIONS.
 */
function process_valoracions($sheet, array $normalized_header, array &$errors, int &$imported_count): array
{
    $valoracions = [];
    $highestRow = $sheet->getHighestRow();

    $expected_cols = [
        'ID_VALORACION' => 'id',
        'USUARIO_ID' => 'user_id',
        'PRODUCTO_ID' => 'product_id',
        'COMENTARIO' => 'comentario',
        'PUNTUACION' => 'puntuacion',
        'ME_GUSTA' => 'megusta',
        'FECHA' => 'fecha_creacion'
    ];
    $col_map = [];

    foreach ($expected_cols as $original_name => $normalized_name) {
        $col_map[$original_name] = array_search(strtolower($original_name), $normalized_header);
    }

    // 3. Recórrer les files de dades (començant per la fila 2)
    for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
        $rowData = [];
        foreach ($col_map as $key => $colIndex) {
            if ($colIndex !== false) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $coordinate = $colLetter . $rowNum;
                $cell = $sheet->getCell($coordinate);
                $rowData[$key] = trim($cell->getFormattedValue());
            }
        }

        // Conversión y validación de datos de valoración
        $puntuacion = (int)($rowData['PUNTUACION'] ?? 0);
        $megusta_val = strtolower($rowData['ME_GUSTA'] ?? 'false');
        $is_like = ($megusta_val === 'true' || $megusta_val === '1' || $megusta_val === 'v');

        if (empty($rowData['USUARIO_ID']) || empty($rowData['PRODUCTO_ID'])) {
            $errors[] = "Fila $rowNum ignorada: Falten ID d'usuari o ID de producte.";
            continue;
        }
        if (empty($rowData['COMENTARIO']) && $puntuacion === 0 && !$is_like) {
            $errors[] = "Fila $rowNum ignorada: Valoració sense contingut (comentari, puntuació o M'agrada).";
            continue;
        }

        $valoracions[] = [
            "id" => $rowData['ID_VALORACION'] ?? 'V' . ($rowNum - 1),
            "user_id" => (int)($rowData['USUARIO_ID'] ?? 0),
            "product_id" => $rowData['PRODUCTO_ID'] ?? 'N/A',
            "comentario" => $rowData['COMENTARIO'] ?? '',
            "puntuacion" => $puntuacion > 0 ? $puntuacion : null, // Guardar NULL si no hay puntuación
            "megusta" => $is_like,
            "fecha_creacion" => $rowData['FECHA'] ?? date('Y-m-d H:i:s')
        ];
        $imported_count++;
    }
    return $valoracions;
}


/**
 * Funció que guarda les dades al JSON. Actualiza la col·lecció especificada i assegura l'existència de les col·leccions base.
 */
function save_json(array $data_to_save, string $filePath, string $collection_name): bool
{
    // 1. Inicializar array para datos existentes
    $existing_data = [];

    // 2. Leer contenido existente (si existe y si es válido)
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $existing_data = json_decode($content, true) ?: [];
    }

    // 3. Actualizar la colección específica
    $existing_data[$collection_name] = $data_to_save;

    // 4. Asegurar que las colecciones necesarias existan si se está importando por primera vez (productes y valoracions)
    if (!isset($existing_data["productes"]) || !is_array($existing_data["productes"])) {
        $existing_data["productes"] = [];
    }
    if (!isset($existing_data["valoracions"]) || !is_array($existing_data["valoracions"])) {
        $existing_data["valoracions"] = [];
    }
    // Nota: Se asume que la colección 'users' ya existe en existing_data si la autenticación está activa.

    // 5. Re-codificar el JSON completo
    $json_content = json_encode($existing_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // 6. Guardar el JSON
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

    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <a href="/" class="logo">GLAMUR CLUB</a>

                <div class="nav-links" id="navLinks">
                    <a href="./catalogo.html">Catálogo</a>
                    <a href="./crear-perfume.html">Crea tu Perfume</a>
                </div>

                <div class="nav-actions">
                    <a href="/favoritos.html" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span class="badge" id="favoritesBadge">0</span>
                    </a>
                    <a href="./carrito.html" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                        </svg>
                        <span class="badge" id="cartBadge">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <div class="import-container">
        <h1 style="color: var(--color-gold); font-family: var(--font-serif); font-size: 2rem; margin-bottom: 1.5rem;">Importar Catàleg (Excel a JSON Server)</h1>

        <?php if (!empty($message)): ?>
            <div class="import-message-success">
                <?= htmlspecialchars($message) ?>
                <p style="margin-top: 0.5rem;">Pots comprovar el JSON generat a: <a href="<?= $TARGET_API_URL_LOCAL ?>" target="_blank" style="color: var(--color-gold-light);">http://localhost:3000/<?= htmlspecialchars($collection_name) ?></a></p>
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
            <p style="color: var(--color-gray-lighter); font-size: 0.875rem;">Aquest script detecta el tipus d'arxiu basant-se en les capçaleres.</p>
            <p style="color: var(--color-gray-lighter); font-size: 0.875rem;">**Capçaleres Esperades:**<br>
                - **Productes:** Nom, Descripcio, Preu, Estoc, SKU, IMG, Categoria, Destacat (8 columnes)<br>
                - **Valoracions:** ID_VALORACION, USUARIO_ID, PRODUCTO_ID, COMENTARIO, PUNTUACION, ME_GUSTA, FECHA (7 columnes)
            </p>
            <label for="excel_file" style="font-weight: 500;">Selecciona el fitxer Excel (.xlsx, .xls, .csv):</label>
            <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required style="
                padding: 0.75rem;
                border-radius: 0.375rem;
                border: 1px solid var(--color-gray-light);
                background-color: var(--color-gray);
                color: var(--color-white);
            ">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                Importar Dades
            </button>
        </form>
    </div>
</body>

</html>