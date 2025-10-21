document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");
  if (!form) return; // si no hay formulario, salir

  const acceptTerms = document.getElementById("acceptTerms");
  const skipValidation = document.getElementById("skipValidation");

  form.addEventListener("submit", (e) => {
    // si el usuario marcó "Desactivar validación", no hacemos nada
    if (skipValidation && skipValidation.checked) {
      return true; // dejar que el formulario se envíe normal
    }

    let errors = [];

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const subject = form.subject.value.trim();
    const message = form.message.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Validación de nombre
    if (name.length < 2)
      errors.push("⚠️ El nombre debe tener al menos 2 caracteres.");
    // Validación de correo
    if (!emailRegex.test(email))
      errors.push("⚠️ El correo electrónico no es válido.");
    // Validación de asunto
    if (subject.length < 3) errors.push("⚠️ El asunto es demasiado corto.");
    // Validación de mensaje
    if (message.length < 5) errors.push("⚠️ El mensaje es demasiado corto.");
    // Validación de términos
    if (!acceptTerms.checked)
      errors.push("⚠️ Debes aceptar los términos y condiciones.");

    if (errors.length > 0) {
      e.preventDefault(); // detener envío
      alert("Revisa los siguientes errores:\n\n- " + errors.join("\n- "));
    }
  });
});
