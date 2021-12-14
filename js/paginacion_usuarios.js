window.addEventListener("load",function()
{
    var tabla = document.getElementById("tabla");

    var cabesa = document.createElement("thead");
    var corpus = document.createElement("tbody");

    var paginas = document.getElementById("paginas");

    var p = 1;
    var urlAEnviar = "traeUsuariosPaginados.php?p="+p+"&t=10";
    fetch(urlAEnviar)
    .then(function(response)
    {
        return response.json();
    })
    .then(function(json)
    {
        escribeCabeceras(json);
        escribePreguntas(json);
        escribePaginas(json);
    });

    function escribeCabeceras(json)
    {
        let cabecera = document.createElement("tr");
        let arrayoMcQueen = ["Nombre", "Rol", "Localidad", "Exámenes realizados", "opciones"]
    }
})