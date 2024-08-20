document.addEventListener("DOMContentLoaded", function () {
  const alertas = document.querySelectorAll(".alerta");
  setTimeout(() => {
    alertas.forEach((alerta) => {
     
      alerta.style.opacity = "0";
      alerta.addEventListener("transitionend", (event) => {
        if (event.propertyName === "opacity") {
          
         
          alerta.remove();
        }
      });
    });
  }, 3000); 
});


const stockMinimoInput = document.getElementById('codigo_orden');

stockMinimoInput.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; 
    }
});

stockMinimoInput.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); 
    }
});

