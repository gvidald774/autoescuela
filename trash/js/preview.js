window.addEventListener("load",function()
{
    preview = document.getElementById("preview");

    formularino = document.getElementsByTagName("form")[0];
    
    img = document.createElement("img");
    preview.appendChild(img);
    archivo = formularino["archivo"];
    archivo.onchange = function()
    {
        var src = URL.createObjectURL(this.files[0]);
        img.src = src;
    }
})