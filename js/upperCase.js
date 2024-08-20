const valorInput = document.getElementById("CodigoAlumno");

valorInput.addEventListener("input", convertirMayuscula);

// Función para convertir a mayúsculas
function convertirMayuscula() {
  valorInput.value = valorInput.value.toUpperCase();
}

// Función para manejar el evento input
function manejarInput() {
  // Eliminar espacios si se ingresan
  valorInput.value = valorInput.value.replace(/\s/g, "");
}

// Agregar el evento input al campo de entrada
valorInput.addEventListener("input", manejarInput);
