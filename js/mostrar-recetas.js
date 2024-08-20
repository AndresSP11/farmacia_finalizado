var seleccionador = document.querySelector("#seleccion_tipo");
var datoInput = document.querySelector("#dato_texto");
const tablero = document.querySelector("#tabla");
document.addEventListener("DOMContentLoaded", porDefecto);
function porDefecto() {
  mostrarTodoLosMedicamentos();
}

seleccionador.addEventListener("change", function () {
  if (seleccionador.value.trim() === "") {
    mostrarTodoLosMedicamentos();
  } else {
    /* AQUI POR NOMBRE DE MEDICAMENTO , EN ESET CASO LA RESPUESTA QUE SE VA OBENER SERÁ POR EL 
        HTML  */
    if (seleccionador.value == "fichador") {
      /* Llamador por nombre */
      tablero.innerHTML =
        "<tr><th>NÚMERO DE <br> RECETA</th><th>CÓDIGO <br> ALUMNO</th><th>PERÍODO <br> ACADEMICO</th><th>ESPECIALISTA <br> MÉDICO</th><th>ESPECIALIDAD</th><th>FECHA DE REGISTRO</th></tr>";

      datoInput.addEventListener("keyup", function () {
        tablero.innerHTML = "";
        soloConNombre(datoInput.value);
      });
    }
    if (seleccionador.value == "codigo-alumno") {
      tablero.innerHTML =
        "<tr><th>NÚMERO DE <br> RECETA</th><th>CÓDIGO <br> ALUMNO</th><th>PERÍODO <br> ACADEMICO</th><th>ESPECIALISTA <br> MÉDICO</th><th>ESPECIALIDAD</th><th>FECHA DE REGISTRO</th></tr>";

      datoInput.addEventListener("keyup", function () {
        tablero.innerHTML = "";
        codigoInterno(datoInput.value);
      });
    }
  }
});

async function mostrarTodoLosMedicamentos() {
  const tablero = document.querySelector("#tabla");
  tablero.innerHTML = "";
  const data = new FormData();
  data.append("post", "todo");
  try {
    const url = "../apis/mostrar-todos-recetas.php";
    const resultado = await fetch(url, {
      method: "POST",
      body: data,
    });
    /* Obtención de los valroes correspondientes */
    const respuesta = await resultado.json();
    const cabeza_1 = document.createElement("tr");
    cabeza_1.innerHTML = `
            <tr>
                                <th>NÚMERO DE <br> RECETA</th>
                                <th>CÓDIGO <br> ALUMNO</th>
                                <th>PERÍODO <br> ACADEMICO</th>
                                <th>ESPECIALISTA <br> MÉDICO</th>
                                <th>ESPECIALIDAD</th>
                                <th>FECHA DE <br>REGISTRO</th>
                            </tr>`;
    tablero.appendChild(cabeza_1);

    respuesta.forEach((element) => {
      var comida = "";
      if (element.cantidad >= element.minimo_stock) {
        comida = "stock-positivo";
      }
      if (element.minimo_stock == "") {
        comida = "";
      }
      if (element.cantidad < element.minimo_stock) {
        comida = "stock-negativo";
      }
      const cabeza = document.createElement("tr");
      /* Aqui esta yendo los valores determinados... */
      cabeza.innerHTML = `
                <td>
                <a href="../pages/receta-id.php?id_receta=${element.fichador_receta}" style="color:#195780">
                ${element.fichador_receta}
                </a>
                </td>
                <td>${element.codigo_alumno}</td>
                <td>${element.periodo_academico}</td>
                <td>${element.nombre_medico}</td>
                <td>${element.especialidad_medico}</td>
                <td>${element.fecha}</td>
                
                `;
      tablero.appendChild(cabeza);
    });
  } catch (error) {
    console.log(error);
  }
}

async function soloConNombre(valor) {
  const data = new FormData();
  data.append("post", valor);
  try {
    const url = "../apis/solo-fichador.php";

    const resultado = await fetch(url, {
      method: "POST",
      body: data,
    });

    const respuesta = await resultado.json();
    tablero.innerHTML = respuesta;
  } catch (error) {
    console.log(error);
  }
}

async function mostrarConCodigoSiga() {
  const data = new FormData();
  data.append("post", valor);
  try {
    const url = "../apis/solo-nombre.php";
    const resultado = await fetch(url, {
      method: "POST",
      body: data,
    });

    const respuesta = await resultado.json();
    tablero.innerHTML = respuesta;
  } catch (error) {
    console.log(error);
  }
}
async function codigoInterno(valor) {
  const data = new FormData();
  data.append("post", valor);
  try {
    const url = "../apis/solo-codigo-receta.php";
    const resultado = await fetch(url, {
      method: "POST",
      body: data,
    });

    const respuesta = await resultado.json();
    tablero.innerHTML = respuesta;
  } catch (error) {
    console.log(error);
  }
}
/* denomin_comun_internac_o_principio_activo */

/* 
<----Tabla medicamentos---->
id_medicamento (pk)
cantidad
precio_unitaario
fecha_vencimiento
codigo_siga (fk)
*/

/* 

Lo que se pide 
<------petitorio-----xd>
id_medicamento (PK)
codigo_siga
denomin_comun_internac_o_principio_activo


*/
