window.addEventListener("load",function()
{
    var areaTexto = document.getElementById("csv");
    var areaError = document.getElementById("error_altaMasiva");
    var boton = document.getElementById("botonAltaMasiva");
    var botonArchivo = document.getElementById("archivoTexto");

    botonArchivo.onchange = function()
    {
        archivo = this.files[0];
        var reader = new FileReader();
        reader.onload = function(progressEvent)
        {
            areaTexto.value = this.result;
            enableButton();
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

    function disableButton()
    {
        areaError.innerHTML = "<div class='mensaje_error'>Algunos de los datos introducidos no son correctos. Por favor, compruebe la entrada.</div>";
        boton.disabled = true;
    }

    function enableButton()
    {
        areaError.innerHTML = "";
        boton.disabled = false;
    }

})