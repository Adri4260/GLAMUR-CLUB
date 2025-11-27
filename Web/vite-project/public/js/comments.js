// Web/vite-project/public/js/comments.js
// Lógica dinámica para la funcionalidad C3: Comentarios y Valoraciones (usando colección /valoracions)

document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Obtención de Elementos del DOM ---
  const productIdElement = document.getElementById("product-id");
  const commentsSection = document.getElementById("product-reviews");

  if (!productIdElement || !commentsSection) {
    console.error("Elementos principales de comentarios no encontrados.");
    return;
  }

  // El ID del producto es el SKU (p001, c001, etc.)
  const productId = productIdElement.value;
  const commentsContainer = document.getElementById("comments-list");
  const commentForm = document.getElementById("comment-form");
  const likeButton = document.getElementById("like-button");
  const statsContainer = document.getElementById("comment-stats");
  const isLoggedIn = commentForm !== null; // Determina si el formulario es visible

  // --- 2. Funciones de Utilidad para Renderizado ---

  // Genera la representación visual de las estrellas
  function generateRatingStars(rating) {
    const fullStar = "⭐";
    const emptyStar = "☆";
    const ratingFloat = parseFloat(rating);
    let stars = "";
    for (let i = 1; i <= 5; i++) {
      stars += i <= ratingFloat ? fullStar : emptyStar;
    }
    return `<span class="rating-stars">${stars}</span>`;
  }

  // Renderiza el HTML de un solo comentario/valoración
  function renderComment(comment) {
    let ratingHtml = "";
    if (comment.puntuacion) {
      ratingHtml = `<div class="comment-rating">${generateRatingStars(
        comment.puntuacion
      )} (${comment.puntuacion}/5)</div>`;
    } else if (comment.megusta === true || comment.megusta === "1") {
      ratingHtml = `<div class="comment-rating"><span role="img" aria-label="Me gusta">👍</span> Me gusta</div>`;
    }

    // Mostrar comentario solo si existe texto real
    const commentText =
      comment.comentario && comment.comentario.length > 0
        ? `<p class="comment-text">${comment.comentario}</p>`
        : "";

    // Asegúrate de que los campos coincidan con la estructura de la API
    return `
            <div class="comment-item">
                ${commentText}
                ${ratingHtml}
                <small class="comment-meta">
                    Escrito por <strong>${comment.username}</strong> el 
                    ${new Date(comment.fecha_creacion).toLocaleDateString()}
                </small>
            </div>
        `;
  }

  // Renderiza el resumen de estadísticas
  function renderStats(stats) {
    if (!statsContainer) return;

    const avgRating = parseFloat(stats.avg_rating).toFixed(1);
    const totalComments = stats.total_comments;
    const totalLikes = stats.total_likes;

    const avgRatingHtml =
      avgRating > 0
        ? `${generateRatingStars(avgRating)} (${avgRating} / 5)`
        : "Sense valoracions (0/5)";

    statsContainer.innerHTML = `
            <h3>Estadístiques de la Comunitat:</h3>
            <p>Valoració mitjana: ${avgRatingHtml}</p>
            <p>Total de valoracions (Comentaris + M'agrada): ${totalComments}</p>
            <p>Total de "M'agrada": ${totalLikes}</p>
        `;
  }

  // --- 3. Lógica de Carga (GET) ---
  function loadComments() {
    if (!commentsContainer) return;

    commentsContainer.innerHTML = "<p>Carregant valoracions...</p>";

    // Llama a la API /api/comments.php con el filtro del producto (SKU)
    fetch(`/api/comments.php?product_id=${productId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Renderizar comentarios
          commentsContainer.innerHTML = "";
          if (data.data.comments.length === 0) {
            commentsContainer.innerHTML =
              "<p>Encara no hi ha valoracions. Sigues el primer a opinar!</p>";
          } else {
            data.data.comments.forEach((comment) => {
              commentsContainer.innerHTML += renderComment(comment);
            });
          }

          // Renderizar estadísticas
          renderStats(data.data.stats);

          // Bloquear las acciones si el usuario ya ha valorado
          if (isLoggedIn && data.data.user_has_commented) {
            const submitButton = commentForm.querySelector(
              'button[type="submit"]'
            );
            if (submitButton) {
              submitButton.disabled = true;
              submitButton.textContent =
                "Ja has enviat una opinió. Només es permet una per usuari.";
            }
            if (likeButton) {
              likeButton.disabled = true;
              likeButton.textContent = "👍 M’agrada (Ja valorat)";
            }
          } else if (isLoggedIn) {
            // Restaurar los botones si está logueado y puede comentar
            const submitButton = commentForm.querySelector(
              'button[type="submit"]'
            );
            if (submitButton) submitButton.disabled = false;
            if (likeButton) likeButton.disabled = false;
          }
        } else {
          commentsContainer.innerHTML = `<p class="error-message">Error en el servidor: ${data.message}</p>`;
        }
      })
      .catch((error) => {
        console.error("Error al cargar valoraciones:", error);
        commentsContainer.innerHTML =
          '<p class="error-message">Error de connexió al carregar valoracions.</p>';
      });
  }

  // --- 4. Lógica de Envío (POST) ---
  function submitComment(formData, button, originalText) {
    button.disabled = true;
    button.textContent = "Enviant...";

    fetch("/api/comments.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (response.status === 401) {
          alert("Debes iniciar sessió per comentar.");
          window.location.href = "/auth/login.php";
          return;
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          alert("Operació realitzada amb èxit!");

          // Recargar para ver el nuevo comentario y las estadísticas actualizadas
          loadComments();

          // Bloquear acciones después del éxito
          if (commentForm) commentForm.reset();
          if (commentForm) {
            commentForm.querySelector('button[type="submit"]').disabled = true;
          }
          if (likeButton) {
            likeButton.disabled = true;
            likeButton.textContent = "👍 M’agrada (Ya valorado)";
          }
        } else {
          alert("Error: " + data.message);
          button.disabled = false;
          button.textContent = originalText;
        }
      })
      .catch((error) => {
        console.error("Error en la petición:", error);
        alert("Error de connexió al servidor.");
        button.disabled = false;
        button.textContent = originalText;
      });
  }

  // --- 5. Manejo de Eventos ---

  // Evento para el formulario de comentario/puntuación
  if (commentForm) {
    commentForm.addEventListener("submit", function (event) {
      event.preventDefault();
      const submitButton = event.submitter;
      const originalText = submitButton.textContent;

      const formData = new FormData(commentForm);
      formData.append("product_id", productId);

      submitComment(formData, submitButton, originalText);
    });
  }

  // Evento para el botón "Me Gusta"
  if (likeButton) {
    likeButton.addEventListener("click", function () {
      const originalText = likeButton.textContent;

      const formData = new FormData();
      formData.append("product_id", productId);
      formData.append("is_like_only", "true");
      // Rellenar estos campos para pasar la validación mínima del backend
      formData.append("comment", "");
      formData.append("rating", "");

      submitComment(formData, likeButton, originalText);
    });
  }

  // Iniciar la carga de comentarios al cargar la página
  loadComments();
});
