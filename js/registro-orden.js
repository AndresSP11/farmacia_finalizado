const codigopet = document.querySelector("#codigo-pet");
const contenedordiv = document.querySelector(".contenedor-divs");

var medicamentosLS = JSON.parse(localStorage.getItem("medicamentos")) || [];

/* Creamos el tipo de evento pero en este caso es mediante tecla lo que va suceder */

codigopet.addEventListener("keyup", mostrarcodigos);

async function mostrarcodigos() {
  try {
    const datos = new FormData();
    datos.append("sol", codigopet.value);
    const url = "../apis/codigo-petitorio.php";
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
/* Finalizado el evento de hacer el llamado a la consultas */

const enviarMedicamentos = document.querySelector("#enviar-medicamentos");
enviarMedicamentos.addEventListener("click", function (e) {
  e.preventDefault();
  var codigoPet = document.getElementById("codigo-pet").value;
  var cantidadPet = document.getElementById("cantidad-pet").value;
  var laboratorioPet = document.getElementById("laboratorio-pet").value;
  var numberLote = document.getElementById("number-lote").value;
  const precioPet = document.querySelector("#preciou-pet").value;
  var fechaVencimientoPet = document.getElementById("fechav-pet").value;
  if (
    codigoPet === "" ||
    cantidadPet === "" ||
    laboratorioPet === "" ||
    fechaVencimientoPet === "" ||
    precioPet === "" ||
    numberLote === ""
  ) {
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
    validandoCodigo(
      codigoPet,
      cantidadPet,
      laboratorioPet,
      fechaVencimientoPet,
      precioPet,
      numberLote
    );
  }
});

async function validandoCodigo(
  codigo,
  cantidad,
  laboratorio,
  fecha,
  precio,
  numberLote
) {
  const url = "../apis/enviar-medicamento-orden.php";
  const datos = new FormData();
  datos.append("codigo", codigo);

  try {
    const resultado = await fetch(url, {
      method: "POST",
      body: datos,
    });
    const respuesta = await resultado.json();
    if (respuesta) {
      const objeto = {
        codigo: codigo,
        cantidad: cantidad,
        laboratorio: laboratorio,
        fecha: fecha,
        precio: precio,
        numberLote: numberLote,
      };
      medicamentosLS.push(objeto);
      localStorage.setItem("medicamentos", JSON.stringify(medicamentosLS));
      setTimeout(() => {
        window.location.href = window.location.href;
      }, 1200);
    }
  } catch (error) {
    console.log(error);
  }
}

/* Mostrar la parte del LocalStorage  */
/* Funcion para mostrar el localStorage */

document.addEventListener("DOMContentLoaded", function () {
  const tabla = document.querySelector("#tabla");
  let variable = 0;
  /* Aqui esta haciendo el forEach de los medicamentos */
  medicamentosLS.forEach(function (medicamento) {
    crearElemento(medicamento);
  });

  function crearElemento(medicamento) {
    const elemento = document.createElement("tr");
    /* Para modificar el precio  */
    const totalMedicina = parseFloat(
      (medicamento.precio * medicamento.cantidad).toFixed(2)
    );
    const dinero = document.querySelector(".monto-total-dinero");
    variable = variable + totalMedicina;
    /* Valor de textContent, del dinero de la parte del montoTotal*/
    dinero.textContent = "S/ " + variable;

    elemento.innerHTML = `
            <td>${medicamento.codigo}</td>
            <td>${medicamento.codigo.split("-")[0]}</td>
            <td>${medicamento.codigo.split("-")[1]}</td>
            <td>S/${medicamento.precio}</td>
            <td>${medicamento.numberLote}</td>
            <td>${medicamento.cantidad}</td> 
            <td>${medicamento.laboratorio}</td> 
            <td>${medicamento.fecha}</td>
            <td>
            <button class='edicion'><i class='bx bx-edit'></i></button>
            <button class='eliminacion'><i class='bx bx-trash'></i</button>
            </td>
        `;
    /* En este caso  */
    tabla.appendChild(elemento);
    const eliminacion = elemento.querySelector(".eliminacion");

    /* Editando el codigo para edit, para ver el nuevo formulario */

    const edicion = elemento.querySelector(".edicion");

    /* En este caso la parte de elimninación de localStorage */
    eliminacion.addEventListener("click", function () {
      const index = medicamentosLS.indexOf(medicamento);
      medicamentosLS.splice(index, 1);
      localStorage.setItem("medicamentos", JSON.stringify(medicamentosLS));
      setTimeout(() => {
        window.location.href = window.location.href;
      }, 600);
    });
    /* AQUI EMPIEZA LA EDICIÓN */
    edicion.addEventListener("click", function () {
      const index = medicamentosLS.indexOf(medicamento);
      const modal = document.createElement("div");
      modal.classList.add("contenedor-div");

      modal.innerHTML = `
        <div class='cartel-aviso'>

          <form  class="form-clas">
                <div class="left">
                    <div class="div-1">
                    <label for="search-type" class="codigo-medicamento-edicion" name="fichador-edicion">Código ATC:</label>
                    <input type="text" id="codigo-edit" value="${medicamento.codigo}" readonly>
                    </div>
                    <div class="div-1">
                    <label for="search-type" class="search-text-registrar" name="precio-edicion">Precio unitario:</label>
                    <input type="text" id="precio-edit" value="${medicamento.precio}"> 
                    </div>
                    <div class="div-1">
                    <label for="search-type" class="search-text-registrar" name="fecha-edicion">Vencimiento:</label>
                    <input type="date" id="fecha-edit" value="${medicamento.fecha}"> 
                    </div>

                </div>
          
                <div class="right">
                    <div class="div-2">
                    <label for="search-input" class="search-text-registrar" name="laboratorio-edicion">Laboratorio:</label>
                    <input type="text" id="laboratorio-edit" value="${medicamento.laboratorio}">
                    </div>
                    <div class="div-2">
                    <label for="search-input" class="search-text-registrar" name="numberLote-edicion">Lote:</label>
                    <input type="text" id="lote-edit" value="${medicamento.numberLote}">
                    </div>
                    <div class="div-2">
                    <label for="search-input" class="search-text-registrar" name="cantidad-edicion">Cantidad:</label>
                    <input type="text" id="cantidad-edit" value="${medicamento.cantidad}">
                    </div>    
                </div>  
          </form>
            <div class="botones-form-edit">
              <button id='denegar-peticion' class='cancelar-peticion'>Cancelar Edición</button>
              <button id='aceptar-peticion' class='aceptar-edicion'>Aceptar Edición</button>
            </div>
          </div>
    `;
      /* Aqui define los parametros */
      document.querySelector("body").appendChild(modal);
      modal.addEventListener("click", function (e) {
        console.log(e.target);
        const inputEdicion = document.querySelector(
          ".codigo-medicamento-edicion"
        );
        inputEdicion.addEventListener("keydown", function (event) {
          event.preventDefault();
        });
        if (e.target.classList.contains("aceptar-edicion")) {
          const index = medicamentosLS.indexOf(medicamento);
          /* AQUI EN PARTE DE LA EDICION COLOCAR LOS VALORES PARA QUE PASE LA VALIDACION CORRECTAMENTE */
          const nuevaCantidad = modal.querySelector("#cantidad-edit").value;
          const nuevoLaboratorio =
            modal.querySelector("#laboratorio-edit").value;
          const nuevoNumeroLote = modal.querySelector("#lote-edit").value;
          const nuevaFecha = modal.querySelector("#fecha-edit").value;
          const nuevoPrecio = modal.querySelector("#precio-edit").value;

          const medicamentos = JSON.parse(localStorage.getItem("medicamentos"));
          if (index !== -1) {
            medicamentos[index].cantidad = nuevaCantidad;
            medicamentos[index].laboratorio = nuevoLaboratorio;
            medicamentos[index].numberLote = nuevoNumeroLote;
            medicamentos[index].fecha = nuevaFecha;
            medicamentos[index].precio = nuevoPrecio;

            localStorage.setItem("medicamentos", JSON.stringify(medicamentos));
            modal.remove();
            setTimeout(() => {
              window.location.href = window.location.href;
            }, 500);
          }
        }
        if (e.target.classList.contains("cancelar-peticion")) {
          setTimeout(() => {
            modal.remove();
          }, 0.5);
        }
      });
    });
  }
});

const botonEnviar = document.querySelector("#enviar-ls-md");
const dinero = document.querySelector(".monto-total-dinero").textContent;

console.log(dinero);

/* Crea el modal  */

botonEnviar.addEventListener("click", function () {
  if (medicamentosLS.length === 0) {
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
    /* Aqui define los parametros */
    document.querySelector("body").appendChild(modal);
    modal.addEventListener("click", function (e) {
      console.log(e.target);
      if (e.target.classList.contains("aceptar-peticion")) {
        const definirParams = obtenerProyecto();
        enviarMedicamentosBS(
          definirParams.codigoOrden,
          definirParams.fechaLlegada,
          definirParams.proveedor,
          dinero
        );
      }
      if (e.target.classList.contains("cancelar-peticion")) {
        setTimeout(() => {
          modal.remove();
        }, 0.5);
      }
    });
  }
});

async function enviarMedicamentosBS(
  codigoOrden,
  fechaLlegada,
  proveedor,
  dinero
) {
  const url = "../apis/enviar-ls.php";
  const datos = new FormData();
  /* Enviando el formato de LocalStorage jeje */
  datos.append("localidad", JSON.stringify(medicamentosLS));
  datos.append("codigoOrden", codigoOrden);
  datos.append("fechaLlegada", fechaLlegada);
  datos.append("proveedor", proveedor);
  datos.append("dinero", dinero);
  try {
    const resultado = await fetch(url, {
      method: "post",
      body: datos,
    });
    const respuesta = await resultado.json();
    if (respuesta.verdadero == true) {
      console.log("Muestra de prueba");
      localStorage.removeItem("medicamentos");
      window.location.href = "../pages/registrar-orden.php";
    }
  } catch (error) {
    console.log(error);
  }
}

function obtenerProyecto() {
  const proyectoParams = new URLSearchParams(window.location.search);
  /* Entra al objeto para poder obtener el valor correspondiente */
  const proyecto = Object.fromEntries(proyectoParams.entries());
  const arreglo = {
    codigoOrden: proyecto["codigo-orden"],
    fechaLlegada: proyecto["fecha-llegada"],
    proveedor: proyecto["proveedor"],
  };
  return arreglo;
}

const contenedor = document.querySelector(".contenedor-mt");
if (medicamentosLS.length > 0) {
  contenedor.classList.remove("desaparecer");
}


const stockMinimoInput = document.getElementById('preciou-pet');

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

const NumerodeLote = document.getElementById('number-lote');

NumerodeLote.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

NumerodeLote.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});

const CantidadPet = document.getElementById('cantidad-pet');
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


