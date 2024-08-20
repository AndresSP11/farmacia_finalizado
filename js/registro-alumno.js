const form=document.querySelector('.formulario-sm');
const registrar=document.querySelector('.registrar');

registrar.addEventListener('click',mandarForm);

console.log(form);
console.log(registrar);


function mandarForm(e){
    e.preventDefault();
    form.submit();

    console.log("Bienvenido mundo");
}