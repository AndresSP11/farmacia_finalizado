const codigopet = document.querySelector("#codigo-farmacia");
const nombre_medicamento = document.querySelector("#nombre-medicamento");
const cantidad_medicamento = document.querySelector("#cantidad-medicamento");
const merma_medicamento = document.querySelector("#merma-medicamento");
const fecha_vencimiento = document.querySelector("#fecha-vencimiento");
const precio_unitario = document.querySelector("#precio-unitario");
const lote_medicamento = document.querySelector("#lote-medicamento");
/* CONTENEDOR DIV  */
const contenedordiv = document.querySelector(".contenedor-divs");
const boton_siguiente = document.querySelector(".botones_siguiente");

codigopet.addEventListener("keyup", mostrarcodigos);

async function mostrarcodigos() {
  console.log("Hola mundo");
  console.log(merma_medicamento.value);
  try {
    const datos = new FormData();
    datos.append("sol", codigopet.value);
    const url = "../apis/por-merma.php";
    /* En esta parte mandamos el metodo de la función mediante el keyup osea por tecla */
    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });
    /* En esta parte vamos a capturar la respuesta del php */
    const resultado = await respuesta.json();
    if (codigopet.value == "") {
      contenedordiv.style.display = "none";
    } else {
      /* Recordar que el contenedorDiv es un div creado vacio que nos permite almacenar los resultados
            al momento de añadirlo.  */
      contenedordiv.style.display = "block";
      contenedordiv.innerHTML = resultado;
      /* Aqui va pasar el codigo html generado por la consulta POST */
      /* Vamos añadir otro tipo de evento a la parte de contenedorDiv, en base a esto es digamos que si se presiona un div con el contenido dentro PERO EN
            este caso la selecciòn o el click que se realiza es de la etiqueta del parrafo */
      contenedordiv.addEventListener("click", function (e) {
        e.preventDefault();
        /* En este caso esttamos haciendo referencia al div con la clase VALOR, si presionamos esto entonces..... */
        if (e.target.classList.contains("valor")) {
          const valorsito = e.target.textContent.trim();
          codigopet.value = valorsito;
          contenedordiv.innerHTML = "";
          var arreglo = valorsito.split("//");
          nombre_medicamento.value = arreglo[2]; // Asignando el valor al campo de entrada de nombre del medicamento
          nombre_medicamento.readOnly = true; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
          nombre_medicamento.style.backgroundColor = "#a3a3a3";
          contenedordiv.innerHTML = "";
          contenedordiv.style.display = "none";
        }
        /* Ah claro , en este caso como no validamos de la parte del contenedos o laetiqueta <p> entonces vmoas simmplemente
                 a if-tear los 2 para que digamos si presionamos simplemente haga caso a esto </p> */
        if (e.target.classList.contains("valorsito")) {
          const valorsito = e.target.textContent.trim();
          codigopet.value = valorsito;
          contenedordiv.innerHTML = "";
          contenedordiv.style.display = "none";
          console.log(arreglo);
          var arreglo = valorsito.split("//");

          const numeroImagen = arreglo[8];
          let sinEspacios = numeroImagen.replace(/\s+/g, '');
          const imagenMerma = document.getElementById("imagen-merma");
          imagenMerma.src = `../MedicamentosImg/${sinEspacios}.jpg`; // Asegúrate de reemplazar 'jpg' con la extensión correcta de tu imagen

          nombre_medicamento.value = arreglo[0]; // Asignando el valor al campo de entrada de nombre del medicamento
          nombre_medicamento.readOnly = true; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
          contenedordiv.innerHTML = "";

          lote_medicamento.value = arreglo[3];
          lote_medicamento.readOnly = true; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
          contenedordiv.innerHTML = "";

          precio_unitario.value = arreglo[5];
          precio_unitario.readOnly = true; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
          contenedordiv.innerHTML = "";

          fecha_vencimiento.value = arreglo[6];
          fecha_vencimiento.readOnly = true; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
          contenedordiv.innerHTML = "";
        }
      });
    }
  } catch (error) {
    console.log(error);
  }
}

/* BOTON PARA ENVIAR LOS VALORES */
boton_siguiente.addEventListener("click", enviarMerma);

async function enviarMerma() {
  const datos = new FormData();
  var variable1 = codigopet.value;
  var partes = variable1.split("//");
  var codigoEnviar = partes[2];

  datos.append("codigo", codigoEnviar);
  datos.append("cantidad", cantidad_medicamento.value);
  datos.append("tipoMerma", merma_medicamento.value);
  datos.append("lote", lote_medicamento.value);

  console.log("Datos a enviar:");
  console.log("codigo:", codigoEnviar);
  console.log("cantidad:", cantidad_medicamento.value);
  console.log("tipoMerma:", merma_medicamento.value);
  console.log("lote:", lote_medicamento.value);

  if (
    codigopet.value == "" ||
    cantidad_medicamento.value == "" ||
    merma_medicamento.value == "" ||
    lote_medicamento.value == ""
  ) {
    /* CONTENEDOR DE MERMA */
    const alertaPrevia = document.querySelector(".error");
    if (alertaPrevia) {
      document.querySelector(".alertas").style.display = "none";
      alertaPrevia.remove();
    }

    const error = document.createElement("DIV");
    error.innerHTML = "Rellenar todo los campos porfavor";
    error.classList.add("error");
    document.querySelector(".alertas").appendChild(error);
    document.querySelector(".alertas").style.display = "block";

    setTimeout(() => {
      document.querySelector(".alertas").style.display = "none";

      error.remove();
    }, 3000);
  } else {
    try {
      const url = "../apis/enviar-la-merma.php";
      const resultado = await fetch(url, {
        body: datos,
        method: "POST",
      });

      const respuesta = await resultado.json();
      console.log("Respuesta del servidor:", respuesta);

      console.log("Imprimieno la respuesta" + respuesta.valor);
      if (respuesta.valor === true) {
        const alertaPrevia = document.querySelector(".correcto-envio");
        if (alertaPrevia) {
          alertaPrevia.remove();
        }
        const correct = document.createElement("DIV");
        correct.innerHTML = "Merma Agregada correctamente";
        correct.classList.add("correcto-envio");
        document.querySelector(".alertas").appendChild(correct);
        document.querySelector(".alertas").style.backgroundColor =
          "rgb(117, 234, 150)";
        document.querySelector(".alertas").style.display = "block";
        nombre_medicamento.readOnly = false; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
        setTimeout(() => {
          document.querySelector(".alertas").style.display = "none";
          document.querySelector(".alertas").style.backgroundColor =
            "rgb(229, 76, 76)";
          correct.remove();
          // Redirigir a la página observarmerma.php después de que el mensaje de confirmación desaparezca
          window.location.href = "observarmerma.php";
        }, 2000);
        codigopet.value = "";
        nombre_medicamento.value = "";
        cantidad_medicamento.value = "";
        merma_medicamento.value = "";
        lote_medicamento.value = "";
        precio_unitario.value = ""; // Resetting the price unit field
        fecha_vencimiento.value = "";
        const imagenMerma = document.getElementById("imagen-merma");
        imagenMerma.src = ""; // Reseteando la imagen
      } else {
        const alertaPrevia = document.querySelector(".correcto-envio");
        if (alertaPrevia) {
          alertaPrevia.remove();
        }
        const correct = document.createElement("DIV");
        correct.innerHTML = "Fallo al momento de mandar";
        correct.classList.add("error");
        document.querySelector(".alertas").appendChild(correct);
        document.querySelector(".alertas").style.display = "block";
        nombre_medicamento.readOnly = false; // Haciendo que el campo de entrada de nombre del medicamento sea de solo lectura
        setTimeout(() => {
          document.querySelector(".alertas").style.display = "none";  
          correct.remove();
        }, 4000);
      }
    } catch (error) {
      console.log("Error en el envío:", error);
    }
  }
}

const CantidadPet = document.getElementById('cantidad-medicamento');
CantidadPet.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

CantidadPet.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});
