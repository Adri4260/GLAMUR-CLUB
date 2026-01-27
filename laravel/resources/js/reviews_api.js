let myModal; // Variable para controlar la ventana

// Función para abrir el modal y cargar datos
function openReviewsModal(productId, productName) {
    // 1. Mostrar la ventana
    const modalEl = document.getElementById("reviewsModal");
    myModal = new bootstrap.Modal(modalEl);
    myModal.show();

    // 2. Poner el título y guardar el ID
    document.getElementById("modalTitle").innerText =
        "Opiniones de: " + productName;
    document.getElementById("modalProductId").value = productId;

    // 3. Limpiar lista y cargar comentarios desde la API
    const list = document.getElementById("reviewsList");
    list.innerHTML = '<p class="text-center text-muted">Cargando...</p>';

    fetch(`/api/reviews/${productId}`)
        .then((response) => response.json())
        .then((data) => {
            list.innerHTML = ""; // Borrar mensaje de carga

            if (data.length === 0) {
                list.innerHTML =
                    '<p class="text-center text-muted">Aún no hay valoraciones. ¡Sé el primero!</p>';
                return;
            }

            // Pintar cada comentario
            data.forEach((review) => {
                const stars = "⭐".repeat(review.rating);
                // Si no hay nombre de usuario, ponemos 'Anónimo'
                const userName = review.user
                    ? review.user.name
                    : "Usuario Anónimo";

                const html = `
                    <div class="card mb-2 bg-light border-0">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <small class="fw-bold">${userName}</small>
                                <small>${stars}</small>
                            </div>
                            <p class="mb-0 small mt-1">${review.comment}</p>
                        </div>
                    </div>
                `;
                list.innerHTML += html;
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            list.innerHTML =
                '<p class="text-danger text-center">Error cargando comentarios.</p>';
        });
}

// Función para cerrar el modal
function closeModal() {
    if (myModal) myModal.hide();
}

// Escuchar el envío del formulario (POST)
document.getElementById("reviewForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Evita que se recargue la página

    const productId = document.getElementById("modalProductId").value;
    const rating = document.getElementById("reviewRating").value;
    const comment = document.getElementById("reviewComment").value;
    const token = document.querySelector('meta[name="csrf-token"]').content; // Token de seguridad

    // Datos a enviar
    const data = {
        product_id: productId,
        rating: rating,
        comment: comment,
    };

    // Llamada a la API para GUARDAR
    fetch("/api/reviews", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token, // Importante para que Laravel sepa que somos nosotros
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            if (!response.ok) throw new Error("Error en la petición");
            return response.json();
        })
        .then((newReview) => {
            // Limpiar el formulario
            document.getElementById("reviewComment").value = "";

            // Recargar los comentarios para ver el nuevo
            openReviewsModal(
                productId,
                document
                    .getElementById("modalTitle")
                    .innerText.replace("Opiniones de: ", ""),
            );

            alert("¡Comentario enviado correctamente!");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert(
                "Hubo un error al enviar tu comentario. Asegúrate de haber iniciado sesión.",
            );
        });
});
