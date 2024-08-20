const codigopet = document.querySelector("#codigo-petitorio");
const stock_minimo = document.querySelector("#stock-minimo");
const contenedordiv = document.querySelector(".contenedor-divs");
const boton_siguiente = document.querySelector(".botones_siguiente");

codigopet.addEventListener("keyup", mostrarcodigos);
boton_siguiente.addEventListener("click", mandarStockmin);

async function mostrarcodigos() {
  console.log("Hola mundo");
  try {
    const datos = new FormData();
    datos.append("sol", codigopet.value);
    const url = "../apis/codigo-petitorio.php";
    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });
    const resultado = await respuesta.json();

    if (codigopet.value == "") {
      contenedordiv.innerHTML = "Introduce un codigo";
      contenedordiv.style.display = "none";
    } else {
      contenedordiv.style.display = "block";
      contenedordiv.innerHTML = resultado;

      contenedordiv.addEventListener("click", function (e) {
        e.preventDefault();
        if (e.target.classList.contains("valor")) {
          const valorsito = e.target.textContent.trim();
          const valor1 = valorsito.split("-");
          codigopet.value = valor1[0] + "-" + valor1[1];
          contenedordiv.innerHTML = "";
          contenedordiv.style.display = "none";
        }
        if (e.target.classList.contains("valorsito")) {
          const valorsito = e.target.textContent.trim();
          const valor1 = valorsito.split("-");
          codigopet.value = valor1[0] + "-" + valor1[1];
          contenedordiv.innerHTML = "";
          contenedordiv.style.display = "none";
        }
      });
    }
  } catch (error) {
    console.log(error);
  }
}

async function mandarStockmin() {
  const datos = new FormData();
  datos.append("codigoPet", codigopet.value.trim());
  datos.append("stockMinimo", stock_minimo.value.trim());

  // Eliminar alertas previas antes de agregar una nueva
  limpiarAlertas();

  if (codigopet.value == "" || stock_minimo.value == "") {
    mostrarAlerta(
      "Rellenar todos los campos por favor",
      "error",
      "rgb(229, 76, 76)"
    );
  } else {
    try {
      const url = "../apis/stock-min.php";
      const resultado = await fetch(url, {
        method: "POST",
        body: datos,
      });
      const respuesta = await resultado.json();

      if (respuesta.valor == false) {
        mostrarAlerta(
          "El código JavaScript ha sido modificado, envíalo correctamente por favor",
          "error",
          "rgb(229, 76, 76)"
        );
      } else {
        mostrarAlerta(
          "Stock Mínimo Modificado correctamente",
          "correcto-envio",
          "rgb(117, 234, 150)"
        );
        codigopet.value = "";
        stock_minimo.value = "";
      }
    } catch (error) {
      console.log(error);
    }
  }
}

function mostrarAlerta(mensaje, tipo, color) {
  const alertaPrevia = document.querySelector(`.${tipo}`);
  if (alertaPrevia) {
    alertaPrevia.remove();
  }
  const alerta = document.createElement("DIV");
  alerta.innerHTML = mensaje;
  alerta.classList.add(tipo);
  document.querySelector(".alertas").appendChild(alerta);
  document.querySelector(".alertas").style.display = "block";
  document.querySelector(".alertas").style.backgroundColor = color;

  setTimeout(() => {
    alerta.remove();
    document.querySelector(".alertas").style.display = "none";
  }, 3000);
}

function limpiarAlertas() {
  const alertas = document.querySelectorAll(".alertas div");
  alertas.forEach((alerta) => alerta.remove());
  document.querySelector(".alertas").style.display = "none";
}

const stockMinimoInput = document.getElementById('stock-minimo');

stockMinimoInput.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

stockMinimoInput.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});