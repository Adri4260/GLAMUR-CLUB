// Web/vite-project/public/js/comments.js
// Lógica dinámica para la funcionalidad C3: Comentarios y Valoraciones (Fetch API y Renderizado)

document.addEventListener("DOMContentLoaded", function () {
  // --- 1. Obtención de Elementos del DOM y Configuración ---
  const productIdElement = document.getElementById("product-id");
  
  if (!productIdElement) {
    console.error("CRITICAL: Elemento #product-id (ID del producto) no encontrado.");
    return;
  }

  const productId = productIdElement.value;
  const commentsContainer = document.getElementById("comments-list");
  const commentForm = document.getElementById("comment-form");
  const likeButton = document.getElementById("like-button");
  const statsContainer = document.getElementById("comment-stats");
  const isLoggedIn = commentForm !== null; 

  // --- 2. Funciones de Utilidad para Renderizado (Estrellas) ---

  /**
   * Genera el HTML de las estrellas para una puntuación.
   * Utiliza caracteres Unicode para las estrellas.
   * @param {number} rating - Puntuación de 1 a 5 (puede ser decimal).
   * @returns {string} HTML con iconos de estrellas y el valor numérico.
   */
  function generateRatingStars(rating) {
    const fullStar = '★'; 
    const emptyStar = '☆';
    const maxRating = 5;

    let starsHtml = '';
    const ratingInteger = Math.round(parseFloat(rating)); // Redondeo al entero más cercano

    for (let i = 1; i <= maxRating; i++) {
        starsHtml += (i <= ratingInteger) ? 
                     `<span class="star full-star">${fullStar}</span>` : 
                     `<span class="star empty-star">${emptyStar}</span>`;
    }

    const ratingRounded = parseFloat(rating).toFixed(1);

    return `
        <span class="rating-stars">${starsHtml}</span>
        <span class="rating-value">(${ratingRounded}/${maxRating})</span>
    `;
  }

  // Renderiza el HTML de un solo comentario/valoración
  function renderComment(comment) {
    // Aseguramos valores por defecto para evitar errores de referencia
    const username = comment.username || 'Usuari Desconegut';
    const date = comment.fecha_creacion ? new Date(comment.fecha_creacion).toLocaleDateString() : 'Sense data';
    const commentText = comment.comentario && comment.comentario.length > 0
        ? `<p class="comment-text mt-2">${comment.comentario}</p>`
        : `<p class="comment-text text-muted fst-italic mt-2">Sense comentari escrit.</p>`;
    
    let ratingHtml = "";
    
    if (comment.puntuacion && comment.puntuacion >= 1) {
        ratingHtml = `<div class="comment-rating d-flex align-items-center mb-1">
                        ${generateRatingStars(comment.puntuacion)}
                      </div>`;
    } else if (comment.megusta === true || comment.megusta === "1") {
        ratingHtml = `<div class="comment-rating mb-1"><span role="img" aria-label="Me gusta">👍</span> Aquest usuari li agrada el producte.</div>`;
    }

    return `
            <div class="comment-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="comment-username mb-0">${username}</h5>
                    <small class="text-muted">${date}</small>
                </div>
                ${ratingHtml}
                ${commentText}
            </div>
        `;
  }

  // Renderiza el resumen de estadísticas (media)
  function renderStats(stats) {
    if (!statsContainer) return;

    const avgRating = parseFloat(stats.avg_rating).toFixed(1);
    const totalComments = stats.total_comments;
    const totalLikes = stats.total_likes;
    
    const avgRatingHtml =
      avgRating > 0
        ? `${generateRatingStars(avgRating)}`
        : "Sense valoracions (0.0 / 5)";

    statsContainer.innerHTML = `
        <h3 class="mt-2">Valoració Mitjana:</h3>
        <div class="d-flex align-items-center gap-3">
            <h1 class="display-4 mb-0">${avgRating} / 5</h1>
            <div>
                <p class="h4 mb-1 rating-stars-large">${avgRatingHtml}</p>
                <p class="text-muted mb-0">Basat en ${totalComments} opinions (inclou M'agrada).</p>
                <p class="text-muted mb-0">Total de "M'agrada": ${totalLikes}</p>
            </div>
        </div>
    `;
}

  // --- 3. Lógica de Carga (GET) ---
  function loadComments() {
    if (!commentsContainer) return;

    commentsContainer.innerHTML = "<p>Carregant valoracions...</p>";

    // Llama a la API /api/comments.php con el ID del producto
    fetch(`/api/comments.php?product_id=${productId}`)
      .then((response) => {
        if (!response.ok) {
            // Manejar errores de servidor (ej. 500)
            return response.json().then(err => { throw new Error(err.message || 'Error del servidor al carregar comentaris.'); });
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Renderizar comentarios
          commentsContainer.innerHTML = "";
          if (data.data.comments.length === 0) {
            commentsContainer.innerHTML =
              "<p>Encara no hi ha valoracions. Sigues el primer a opinar!</p>";
          } else {
            // Asegurar que el contenidor de comentaris s'adapti a Bootstrap
            if (!commentsContainer.classList.contains('mt-4')) {
                commentsContainer.classList.add('mt-4');
            }
            data.data.comments.forEach((comment) => {
              commentsContainer.innerHTML += renderComment(comment);
            });
          }

          // Renderizar estadísticas (Mitjana)
          renderStats(data.data.stats);

          // Bloquear las acciones si el usuario ya ha valorado
          if (isLoggedIn && data.data.user_has_commented) {
            const submitButton = commentForm.querySelector('button[type="submit"]');
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
            const submitButton = commentForm.querySelector('button[type="submit"]');
            if (submitButton) submitButton.disabled = false;
            if (likeButton) likeButton.disabled = false;
          }
        } else {
          commentsContainer.innerHTML = `<p class="error-message alert alert-danger">Error en la API de Comentaris: ${data.message}</p>`;
        }
      })
      .catch((error) => {
        console.error("Error al cargar valoraciones (Fetch):", error);
        commentsContainer.innerHTML =
          `<p class="error-message alert alert-danger">Error de connexió: ${error.message}</p>`;
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
        if (!response.ok) {
             return response.json().then(err => { throw new Error(err.message || 'Error del servidor.'); });
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Recargar para ver el nuevo comentario y las estadísticas actualizadas
          loadComments();

          // Bloquear acciones después del éxito
          if (commentForm) commentForm.reset();
          if (commentForm) {
            const submitButton = commentForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = "Ja has enviat una opinió. Només es permet una per usuari.";
            }
          }
          if (likeButton) {
            likeButton.disabled = true;
            likeButton.textContent = "👍 M’agrada (Ja valorat)";
          }
        } else {
          alert("Error: " + data.message);
          button.disabled = false;
          button.textContent = originalText;
        }
      })
      .catch((error) => {
        console.error("Error en la petición:", error);
        alert(`Error de connexió al servidor: ${error.message}`);
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