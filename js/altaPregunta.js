window.addEventListener("load",function()
{
    imagenPreview = document.getElementById("imagen");

    formularino = document.getElementsByTagName("form")[0];

    archivo = formularino["recurso"];
    archivo.onchange = function()
    {
        var src = URL.createObjectURL(this.files[0]);
        imagenPreview.src = src;
    }
})