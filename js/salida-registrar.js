const codigopet = document.querySelector("#nombre-pet");
const contenedordiv = document.querySelector(".contenedor-divs");

var salidaMedicamentos =
  JSON.parse(localStorage.getItem("salida-medicamentos")) || [];

/* Creamos el tipo de evento pero en este caso es mediante tecla lo que va suceder */

codigopet.addEventListener("keyup", mostrarcodigos);

async function mostrarcodigos() {
  console.log("ENVIANDO DATOS");
  try {
    const datos = new FormData();
    datos.append("sol", codigopet.value);
    const url = "../apis/codigo-medicamento.php";
    /* En esta parte mandamos el metodo de la función mediante el keyup osea por tecla */
    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });
    /* En esta parte vamos a capturar la respuesta del php */
    const resultado = await respuesta.json();
    if (codigopet.value == "") {
      contenedordiv.style.display = "none";
      contenedordiv.innerHTML = "";
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
          contenedordiv.style.display = "none";
        }
        /* Ah claro , en este caso como no validamos de la parte del contenedos o laetiqueta <p> entonces vmoas simmplemente
                 a if-tear los 2 para que digamos si presionamos simplemente haga caso a esto </p> */
        if (e.target.classList.contains("valorsito")) {
          const valorsito = e.target.textContent.trim();
          codigopet.value = valorsito;
          contenedordiv.innerHTML = "";
          contenedordiv.style.display = "none";
        }
      });
    }
  } catch (error) {
    console.log(error);
  }
}

const enviarMedicamentos = document.querySelector("#salidaReg");
enviarMedicamentos.addEventListener("click", function (e) {
  e.preventDefault();
  var nombreMedicamento = document.getElementById("nombre-pet").value.trim();
  var cantidad = document.getElementById("cantidadMed").value.trim();
  if (cantidad === "" || nombreMedicamento === "") {
    const alertaPrevia = document.querySelector(".error");
    if (alertaPrevia) {
      alertaPrevia.remove();
    }

    const error = document.createElement("DIV");
    error.innerHTML = "Rellenar todo los campos porfavor";
    error.classList.add("error");
    document.querySelector(".alertas").appendChild(error);
    document.querySelector(".alertas").style.display = "block";

    setTimeout(() => {
      error.remove();
      document.querySelector(".alertas").style.display = "none";
    }, 3000);
  } else {
    if (cantidad <= 0) {
      const alertaPrevia = document.querySelector(".error");
      if (alertaPrevia) {
        alertaPrevia.remove();
      }
      const error = document.createElement("DIV");
      error.innerHTML = "Introducir valores positivos";
      error.classList.add("error");
      document.querySelector(".alertas").appendChild(error);
      document.querySelector(".alertas").style.display = "block";

      setTimeout(() => {
        error.remove();
        document.querySelector(".alertas").style.display = "none";
      }, 3000);
    } else {
      console.log(nombreMedicamento + " Mostrando los servicios " + cantidad);
      var partes = nombreMedicamento.split("-");
      var codigoMedicamento = partes[2];
      medicamentoSalidaVerificaCantidad(codigoMedicamento, cantidad);
    }
  }
});

async function medicamentoSalidaVerificaCantidad(codigo, cantidad) {
  var datos = new FormData();
  datos.append("codigo", codigo);
  datos.append("cantidad", cantidad);

  try {
    const url = "../apis/codigo-salida.php";
    const resultado = await fetch(url, {
      method: "POST",
      body: datos,
    });
    const respuesta = await resultado.json();

    if (respuesta.condicional == false) {
      const alertaPrevia = document.querySelector(".error");
      if (alertaPrevia) {
        alertaPrevia.remove();
      }

      const error = document.createElement("DIV");
      if (respuesta.cantidadMedicamentos) {
        error.innerHTML =
          respuesta.mensaje +
          "en stock solo hay: " +
          respuesta.cantidadMedicamentos;
      } else {
        error.innerHTML = respuesta.mensaje;
      }
      error.classList.add("error");
      document.querySelector(".alertas").appendChild(error);
      document.querySelector(".alertas").style.display = "block";

      setTimeout(() => {
        error.remove();
        document.querySelector(".alertas").style.display = "none";
      }, 6000);
    } else {
      // Verificar si el medicamento ya está en la lista
      const medicamentoExistente = salidaMedicamentos.find(
        (med) => med.codigoMedi === respuesta.codigoMedicamento
      );

      if (medicamentoExistente) {
        alert("¡Este medicamento ya está en la lista!");
      } else {
        var totalDinero = respuesta.precioUnitario * cantidad;
        var objeto = {
          codigoMedi: `${respuesta.codigoMedicamento}`,
          codigo: `${respuesta.codigoSiga}`,
          nombre: `${respuesta.nombreMed}`,
          cantidad: cantidad,
          precioUni: `${respuesta.precioUnitario}`,
          total: totalDinero,
        };

        salidaMedicamentos.push(objeto);
        localStorage.setItem(
          "salida-medicamentos",
          JSON.stringify(salidaMedicamentos)
        );
        setTimeout(() => {
          window.location.href = window.location.href;
        }, 1000);
      }
    }
  } catch (error) {
    console.log(error);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const tabla = document.querySelector(".tabla");
  let variable = 0;
  salidaMedicamentos.forEach(function (medicamento) {
    crearElemento(medicamento);
  });

  function crearElemento(medicamento) {
    const elemento = document.createElement("tr");
    const totalMedicina = parseFloat(
      (medicamento.precioUni * medicamento.cantidad).toFixed(2)
    );
    const dinero = document.querySelector(".parrafo");
    variable = variable + totalMedicina;
    var dineroFinal = parseFloat(variable.toFixed(2));
    dinero.textContent = "S/ " + dineroFinal;
    elemento.innerHTML = `
            <td>${medicamento.codigoMedi}</td>
            <td>${medicamento.codigo}</td>
            <td>${medicamento.nombre}</td> 
            <td>${medicamento.cantidad}</td>
            <td>S/${medicamento.precioUni}</td>
            <td>${totalMedicina}</td>
            <td><button class='eliminacion'><i class='bx bx-trash'></button></td>
        `;
    console.log(elemento);
    tabla.appendChild(elemento);
    const eliminacion = elemento.querySelector(".eliminacion");
    eliminacion.addEventListener("click", function () {
      const index = salidaMedicamentos.indexOf(medicamento);
      salidaMedicamentos.splice(index, 1);
      localStorage.setItem(
        "salida-medicamentos",
        JSON.stringify(salidaMedicamentos)
      );
      setTimeout(() => {
        window.location.href = window.location.href;
      }, 600);
    });
  }
});

/* const botonAgre=document.querySelector('#salidaReg');

botonAgre.addEventListener('click',recargarPagina);

function recargarPagina(){
    setTimeout(()=>{
        window.location.href=window.location.href;
    },600)
} */

const botonEnviar = document.querySelector("#enviar-ls-md");

/* Crea el modal  */

botonEnviar.addEventListener("click", function () {
  if (salidaMedicamentos.length === 0) {
    const error = document.createElement("DIV");
    error.innerHTML = "No hay nada para enviar, rellenar la orden";
    error.classList.add("error");
    document.querySelector(".alertas").appendChild(error);
    document.querySelector(".alertas").style.display = "block";

    setTimeout(() => {
      error.remove();
      document.querySelector(".alertas").style.display = "none";
    }, 3000);
  } else {
    console.log("Comida");
    const modal = document.createElement("div");
    modal.classList.add("contenedor-div");

    modal.innerHTML = `
          <div class='cartel-aviso'>
          <p>¿Confirmar Cambios?</p>
            <div class="botones-orden">
              <button id='denegar-peticion' class='cancelar-peticion'>Cancelar</button>
              <button id='aceptar-peticion' class='aceptar-peticion'>Aceptar</button>

            </div>
        </div>
    `;

    document.querySelector("body").appendChild(modal);
    modal.addEventListener("click", function (e) {
      console.log(e.target);
      if (e.target.classList.contains("aceptar-peticion")) {
        /* Aqui vamos a obtener el link con el numero de receta, obtener el valor del doctor
              el codigo de alumno por mientras también etc etc. */
        const definirParams = obtenerProyecto();
        console.log(definirParams);
        enviarSalidaMedicamentos(
          definirParams.fichador,
          definirParams.dniMed,
          definirParams.fecha,
          definirParams.codigoAlum
        );
      }
      if (e.target.classList.contains("cancelar-peticion")) {
        modal.remove();
        setTimeout(() => {
          modal.remove();
        }, 0.5);
      }
    });
  }
});

function obtenerProyecto() {
  const proyectoParams = new URLSearchParams(window.location.search);
  /* Entra al objeto para poder obtener el valor correspondiente */
  const proyecto = Object.fromEntries(proyectoParams.entries());
  const arreglo = {
    fichador: proyecto["fichador"],
    dniMed: proyecto["dniMed"],
    fecha: proyecto["fecha"],
    codigoAlum: proyecto["codigoAlum"],
  };
  return arreglo;
}

async function enviarSalidaMedicamentos(fichador, dniMed, fecha, codigoAlum) {
  const data = new FormData();
  data.append("salidaMedicamentos", JSON.stringify(salidaMedicamentos));
  data.append("fichador", fichador);
  data.append("dniMed", dniMed);
  data.append("fecha", fecha);
  data.append("codigoAlum", codigoAlum);
  try {
    /* En esta parte pasaremos a */
    const url = "../apis/enviar-salida.php";
    const resultado = await fetch(url, {
      body: data,
      method: "POST",
    });
    const respuesta = await resultado.json();

    if (respuesta.verdadero === true) {
      const modalidad = document.querySelector(".contenedor-div");
      modalidad.remove();
      const modal = document.createElement("div");
      modal.classList.add("contenedor-div");

      modal.innerHTML = `
                <div class='cartel-aviso'>
                    <h1 style="color:blue; font-weigth=800;">Agregado Correctamente</h1>
                    <img src="../img/paso.png" width="152" height="140">
                    <p style="font-size:20px; margin-top="10px"";>Alumno ha sido registrado correctamente</p>
                </div>
            `;
      document.querySelector("body").appendChild(modal);

      setTimeout(() => {
        localStorage.setItem("salida-medicamentos", JSON.stringify([]));
        window.location.href = "../pages/new-inicio.php";
      }, 1500);
    }
    /* ###### TRABAJO A AREALIZAR ###### */

    /* Aqui podemos remover toda la información interna de la parte 
        del div, que esto permita er que el registro fue exitoso , tenemos que eliminar los datos
        de localStorage, luego ir a la parte de fichador nuevamente redireccionadolo al fichador.
        */
    /* En esta parte tenemos qeu vaciar la parte de localStorage almacenado  */
  } catch (error) {
    console.log(error);
  }
}

const CantidadPet = document.getElementById('cantidadMed');
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
