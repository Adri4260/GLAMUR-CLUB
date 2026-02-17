let myModal;

window.openReviewsModal = function (productId, productName) {
    const modalEl = document.getElementById("reviewsModal");

    if (typeof bootstrap === "undefined") {
        console.error("Bootstrap JS no está cargado.");
        alert("Error técnico: Bootstrap no cargó correctamente.");
        return;
    }

    myModal = new bootstrap.Modal(modalEl);
    myModal.show();

    document.getElementById("modalTitle").innerText =
        "Opiniones de: " + productName;
    document.getElementById("modalProductId").value = productId;

    const list = document.getElementById("reviewsList");
    list.innerHTML =
        '<div class="text-center p-3 text-muted">Cargando comentarios...</div>';

    fetch(`/api/reviews/${productId}`)
        .then((response) => response.json())
        .then((data) => {
            list.innerHTML = "";

            if (data.length === 0) {
                list.innerHTML =
                    '<p class="text-center text-muted p-3">Aún no hay valoraciones. ¡Sé el primero en opinar!</p>';
                return;
            }

            data.forEach((review) => {
                const stars = "⭐".repeat(review.rating);
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

window.closeModal = function () {
    if (myModal) myModal.hide();
};

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("reviewForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const productId = document.getElementById("modalProductId").value;
            const rating = document.getElementById("reviewRating").value;
            const comment = document.getElementById("reviewComment").value;

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.content : "";

            const data = {
                product_id: productId,
                rating: rating,
                comment: comment,
            };

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
                    document.getElementById("reviewComment").value = "";

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
