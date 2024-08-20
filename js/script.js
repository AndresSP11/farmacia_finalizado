const formulario=document.getElementById('miFormulario');

const boton=document.getElementById('boton');



boton.addEventListener('click',llamandoFuncion);

function llamandoFuncion(e){
    /* Previene la acción por defecto del valor  */
    e.preventDefault();

    formulario.submit();
}