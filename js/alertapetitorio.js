document.addEventListener("DOMContentLoaded", function () {
  const alertas = document.querySelectorAll(".alerta");
  console.log("Alertas encontradas:", alertas); // Debug: Ver cuántas alertas se encontraron

  setTimeout(() => {
    alertas.forEach((alerta) => {
      console.log("Aplicando opacidad 0 a:", alerta); // Debug: Ver cuál alerta está siendo procesada
      alerta.style.opacity = "0";
      alerta.addEventListener("transitionend", (event) => {
        if (event.propertyName === "opacity") {
          // Verifica que la transición que terminó es la de opacidad
          console.log("Transición de opacidad terminada para:", alerta); // Debug: Confirmar que la transición terminó
          alerta.remove();
        }
      });
    });
  }, 3000); // 3 segundos

  // Cambiar el color de fondo de la alerta de éxito
  alertas.forEach((alerta) => {
    if (alerta.textContent.trim() === "Agregado Correctamente") {
      alerta.style.backgroundColor = "rgb(117, 234, 150)";
    }
  });
});

const numberLote = document.getElementById('number-lote');

numberLote.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

numberLote.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});