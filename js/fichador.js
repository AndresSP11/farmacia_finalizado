const hijoFormulario=document.querySelector('.hijo-formulario');
const botonEnvio=document.querySelector('#envio-form');

botonEnvio.addEventListener('click', function() {
    hijoFormulario.submit();
});

const fechaHoy = new Date();

// Ajustar la zona horaria a Perú (UTC-5 durante el horario estándar, UTC-5 durante el horario de verano)
fechaHoy.setUTCHours(fechaHoy.getUTCHours() - 5);

// Obtener el día, mes y año
const dd = String(fechaHoy.getDate()).padStart(2, '0');
const mm = String(fechaHoy.getMonth() + 1).padStart(2, '0'); // Enero es 0!
const yyyy = fechaHoy.getFullYear();

// Formatear la fecha como dd/mm/aaaa
const fechaFormateada = yyyy + '-' + mm + '-' + dd; // Formato ISO 8601 (AAAA-MM-DD)

// Asignar la fecha formateada al input date con la clase 'fecha-modificar'
document.querySelector('#fecha-modificar').value = fechaFormateada;