
var seleccionador = document.querySelector('#seleccion_tipo');
var datoInput=document.querySelector('#dato_texto');
const tablero=document.querySelector('#tabla');
document.addEventListener('DOMContentLoaded',porDefecto)
function porDefecto(){
    mostrarTodoLosMedicamentos();
}

seleccionador.addEventListener('change', function() {
    if (seleccionador.value.trim() === "") {
        mostrarTodoLosMedicamentos();
    } else {
        /* AQUI POR NOMBRE DE MEDICAMENTO , EN ESET CASO LA RESPUESTA QUE SE VA OBENER SERÁ POR EL 
        HTML  */
        if(seleccionador.value=='medicamento'){
            /* Llamador por nombre */
            tablero.innerHTML='<tr><th>Codigo</th><th>Nombre Med</th><th>Cantidad</th><th>Stock</th></tr>';
            
            datoInput.addEventListener('keyup',function(){
                tablero.innerHTML=''
                soloConNombre(datoInput.value);
                
            });
        }
        if(seleccionador.value=='codigo-siga'){
            
            tablero.innerHTML='<tr><th>Codigo</th><th>Nombre Med</th><th>Cantidad</th><th>Stock</th></tr>';
            
            datoInput.addEventListener('keyup',function(){
                tablero.innerHTML=''
                codigoInterno(datoInput.value);
                
            });


        }
    }
});

async function mostrarTodoLosMedicamentos(){
    const tablero=document.querySelector('#tabla');
    tablero.innerHTML='';
    const data= new FormData();
    data.append('post','todo');
    try {
        const url='../apis/codigo-inventario.php';
        const resultado=await fetch(url,{
            method:'POST',
            body:data
        })
        const respuesta=await resultado.json();
        const cabeza_1=document.createElement('tr');
            cabeza_1.innerHTML=`
            <tr>
                                <th>Cod DIGEMID</th>
                                <th>Cod Interno</th>
                                <th>Medicamento</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>F.Vencimiento</th>
                                <th>Estado</th>
                            </tr>`;
            tablero.appendChild(cabeza_1);

        respuesta.forEach(element => {
            var comida=''
            if(element.cantidad>=20){
                comida='stock-positivo';
            }if(element.cantidad>=10 && element.cantidad<19){
                comida='stock-promedio'
            }if(element.cantidad<10){
                comida='stock-negativo'
            }
            const cabeza=document.createElement('tr');

            cabeza.innerHTML=`
            <td>${element.id_medicamento}</td>
                <td>${element.codigo_siga}</td>
                <td>${element.denomin_comun_internac_o_principio_activo}</td>
                <td>S/${element.precio_unitaario}</td>
                <td>${element.cantidad}</td>
                <td>${element.fecha_vencimiento}</td>
                <td class='${comida}'>Stock</td>
                `;
            tablero.appendChild(cabeza);
        });

    } catch (error) {
        console.log(error);
    }
}

async function soloConNombre(valor){
    
    const data= new FormData();
    data.append('post',valor);
    try {
        const url='../apis/solo-nombre.php';
        const resultado= await fetch(url,{
            method:'POST',
            body:data
        })

        const respuesta= await resultado.json();
        tablero.innerHTML=respuesta;
        
    } catch (error) {
        console.log(error);
    }
}

async function mostrarConCodigoSiga(){
    const data= new FormData();
    data.append('post',valor);
    try {
        const url='../apis/solo-nombre.php';
        const resultado= await fetch(url,{
            method:'POST',
            body:data
        })

        const respuesta= await resultado.json();
        tablero.innerHTML=respuesta;
        
    } catch (error) {
        console.log(error);
    }
}
async function codigoInterno(valor){
    const data= new FormData();
    data.append('post',valor);
    try {
        const url='../apis/solo-codigo.php';
        const resultado= await fetch(url,{
            method:'POST',
            body:data
        })

        const respuesta= await resultado.json();
        tablero.innerHTML=respuesta;
        
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