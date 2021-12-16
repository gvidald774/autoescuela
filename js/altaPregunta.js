window.addEventListener("load",function()
{
    preview = document.getElementById("recursoCaja");
    caja_recurso = document.getElementById("caja_recurso");

    formularino = document.getElementsByTagName("form")[0];

    archivo = formularino["recurso"];
    archivo.onchange = function()
    {
        console.log(this.files[0]);
        var fuenteArchivo = URL.createObjectURL(this.files[0]);
        if(this.files[0].type=="video/mp4")
        {
            caja_recurso.innerHTML = "<video controls>"
                                    +"<source src="+fuenteArchivo+">"
                                    +"</video>";
        }
        else
        {
            caja_recurso.innerHTML = "";
            preview = document.createElement("img");
            preview.src = fuenteArchivo;
            caja_recurso.appendChild(preview);
        }
        
    }
})