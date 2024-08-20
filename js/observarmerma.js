const inputMerma=document.querySelector('.input-delamerma');
const tbodycuerpo=document.querySelector('.proceso-get');


inputMerma.addEventListener('keyup',llamadaDeFuncion);

function llamadaDeFuncion(){
    tbodycuerpo.innerHTML='';
    if(inputMerma.value===''){
        peticionInformacion();
    }else{
        peticionInformacion();
    }
}

async function peticionInformacion(){
    const data=new FormData();
    data.append('valorInput',inputMerma.value);
    const url='../apis/solo-merma.php';
        
    try {
        const resultado=await fetch(url,{
            method:'POST',
            body:data
        })
        const respuesta=await resultado.json();
        tbodycuerpo.innerHTML=respuesta;
    } catch (error) {
        console.log(error);
    }
}