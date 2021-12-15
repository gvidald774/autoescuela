window.addEventListener("load",function()
{
    var areaTexto = document.getElementById("csv");
    var areaError = document.getElementById("error");
    var boton = document.getElementById("botonAltaMasiva");
    var botonArchivo = document.getElementById("archivoTexto");

    botonArchivo.onchange = function()
    {
        archivo = this.files[0];
        var reader = new FileReader();
        reader.onload = function(progressEvent)
        {
            areaTexto.value = this.result;
            compruebaCorreo(areaTexto);
        };
        reader.readAsText(archivo);
    }

    areaTexto.onkeyup = function()
    {
        enableButton();
        compruebaCorreo(areaTexto);
    }
    areaTexto.onpaste = function()
    {
        enableButton();
        compruebaCorreo(areaTexto);
    }
    areaTexto.onchange = function()
    {
        enableButton();
        compruebaCorreo(areaTexto);
    }

    function compruebaCorreo()
    {
        let texto = areaTexto.value;
        let lineas = texto.split('\n');
        
        for (let i = 0; i < lineas.length; i++)
        {
            if(!esCorreoIndividual(lineas[i]))
            {
                disableButton();
            }
        }
        
    }

    function esCorreoIndividual(texto)
    {
        let regexp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

        let resultado = false;
        if(regexp.test(texto))
        {
            resultado = true;
        }
        
        return resultado;
    }

    function disableButton()
    {
        areaError.innerHTML = "Algunos de los datos introducidos no son correctos. Por favor, compruebe la entrada.";
        boton.disabled = true;
    }

    function enableButton()
    {
        areaError.innerHTML = "";
        boton.disabled = false;
    }

    // Debería añadir el botón para subir un archivo o algo así. -> parecido a lo de la imagen, pero que el contenido del archivo vaya al textarea.

})