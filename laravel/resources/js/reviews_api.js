// Variable global para controlar la instancia del modal
let myModal;

// EXPORTAMOS LA FUNCIÓN A 'WINDOW' PARA QUE EL HTML PUEDA VERLA
// (Esto soluciona el problema de que el botón no haga nada)
window.openReviewsModal = function (productId, productName) {
    // 1. Obtener el elemento del modal
    const modalEl = document.getElementById("reviewsModal");

    // Seguridad: Si Bootstrap no está cargado, avisamos
    if (typeof bootstrap === "undefined") {
        console.error("Bootstrap JS no está cargado.");
        alert("Error técnico: Bootstrap no cargó correctamente.");
        return;
    }

    // 2. Crear instancia y mostrar
    myModal = new bootstrap.Modal(modalEl);
    myModal.show();

    // 3. Poner título y guardar ID oculto
    document.getElementById("modalTitle").innerText =
        "Opiniones de: " + productName;
    document.getElementById("modalProductId").value = productId;

    // 4. Limpiar lista anterior y mostrar "Cargando..."
    const list = document.getElementById("reviewsList");
    list.innerHTML =
        '<div class="text-center p-3 text-muted">Cargando comentarios...</div>';

    // 5. Pedir datos a la API
    fetch(`/api/reviews/${productId}`)
        .then((response) => response.json())
        .then((data) => {
            list.innerHTML = ""; // Limpiar mensaje de carga

            if (data.length === 0) {
                list.innerHTML =
                    '<p class="text-center text-muted p-3">Aún no hay valoraciones. ¡Sé el primero en opinar!</p>';
                return;
            }

            // Pintar cada comentario
            data.forEach((review) => {
                const stars = "⭐".repeat(review.rating);
                // Si el usuario viene null (borrado), ponemos 'Anónimo'
                const userName = review.user
                    ? review.user.name
                    : "Usuario Anónimo";

                const html = `
                    <div class="card mb-3 border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">${userName}</span>
                                <span class="small">${stars}</span>
                            </div>
                            <p class="mb-0 text-secondary small" style="line-height: 1.4;">
                                ${review.comment}
                            </p>
                        </div>
                    </div>
                `;
                list.innerHTML += html;
            });
        })
        .catch((error) => {
            console.error("Error cargando reviews:", error);
            list.innerHTML =
                '<p class="text-danger text-center p-3">Error al cargar los comentarios.</p>';
        });
};

// Función global para cerrar el modal
window.closeModal = function () {
    if (myModal) myModal.hide();
};

// Listener para el formulario de envío (Se ejecuta cuando la página ha cargado)
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("reviewForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Evita recargar la página

            const productId = document.getElementById("modalProductId").value;
            const rating = document.getElementById("reviewRating").value;
            const comment = document.getElementById("reviewComment").value;

            // Obtener Token CSRF (Seguridad de Laravel)
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.content : "";

            // Datos a enviar
            const data = {
                product_id: productId,
                rating: rating,
                comment: comment,
            };

            // Enviar a la API
            fetch("/api/reviews", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    Accept: "application/json",
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    if (response.status === 401) {
                        throw new Error("Debes iniciar sesión para comentar.");
                    }
                    if (!response.ok) {
                        throw new Error("Error al guardar.");
                    }
                    return response.json();
                })
                .then((newReview) => {
                    // Limpiar el campo de texto
                    document.getElementById("reviewComment").value = "";

                    // Recargar los comentarios para ver el nuevo (usando el título actual para sacar el nombre)
                    const currentName = document
                        .getElementById("modalTitle")
                        .innerText.replace("Opiniones de: ", "");
                    window.openReviewsModal(productId, currentName);

                    alert("¡Gracias por tu comentario!");
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert(error.message || "Hubo un error desconocido.");
                });
        });
    }
});
