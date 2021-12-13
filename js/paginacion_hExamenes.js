window.addEventListener("load",function()
{
    var permisos = document.getElementById("permisos").innerHTML;
    // TODO Hay que añadir la cuarta columna de acciones
    var tabla = document.getElementById("tabla");

    var cabesa = document.createElement("thead");
    var corpus = document.createElement("tbody");
    
    var paginas = document.getElementById("paginas");

    // Esto tengo que mirármelo.
    var p = 1;
    var urlAEnviar = "trae_hExamenesPaginados.php?p="+p+"&t=10&r='"+permisos+"'";
    fetch(urlAEnviar)
    .then(function(response)
    {
        return response.json();
    })
    .then(function(json)
    {
        escribeCabeceras(json);
        escribeExamenes(json);
        escribePaginas(json);
    });
    
    function escribeCabeceras(json)
    {
        let cabecera = document.createElement("tr");
        let arrayoMcQueen = [];
        if(permisos == "Admin")
        {
            arrayoMcQueen = ["fecha", "nombre del alumno", "calificación", "opciones"];
        }
        else if(permisos == "Alumno")
        {
            arrayoMcQueen = ["fecha", "calificación", "opciones"];
        }
        for (let i = 0; i < arrayoMcQueen.length; i++)
        {
            let columna = document.createElement("th");
            columna.innerHTML = arrayoMcQueen[i];
            cabecera.appendChild(columna);
        }
        cabesa.appendChild(cabecera);
    }

    function escribeExamenes(json)
    {
        let arrayoMcQueen = [];
        if(permisos == "Admin")
        {
            arrayoMcQueen = ["fecha", "nombre", "calificacion", "opciones"];
        }
        else if(permisos == "Alumno")
        {
            arrayoMcQueen = ["fecha", "calificacion", "opciones"];
        }
        for (let i = 0; i < Object.keys(json).length-1; i++)
        {
            let fila = document.createElement("tr");
            fila.idExamen = json[i]["id"];
            for (let j = 0; j < arrayoMcQueen.length; j++)
            {
                if(arrayoMcQueen[j]=="opciones")
                {
                    let contenido = document.createElement("td");
                    contenido.style = "font-size: small";
                    let enlaceRevisar = document.createElement("a");
                    let id = json[i]["id"];
                    enlaceRevisar.href = "examen.php?examenRealizado="+fila.idExamen;
                    enlaceRevisar.innerHTML = "Revisar";
                    contenido.appendChild(enlaceRevisar);
                    fila.appendChild(contenido);
                }
                else
                {
                    let contenido = document.createElement("td");
                    contenido.innerHTML = json[i][arrayoMcQueen[j]];
                    if(arrayoMcQueen[j]=="nombre")
                    {
                        contenido.innerHTML += " "+json[i]["apellidos"];
                    }
                    fila.appendChild(contenido);
                }
            }
            corpus.appendChild(fila);
        }   
    }

    function escribePaginas(json)
    {
        for(let i = 1; i <= json.npag; i++)
        {
            enlacePagina = document.createElement("a");
            enlacePagina.innerHTML = i;
            enlacePagina.href = "#";
            enlacePagina.style = "margin: 10px; ";
            enlacePagina.onclick = function()
            {
                removeAllChildNodes(corpus);
                urlAEnviar = "trae_hExamenesPaginados.php?p="+i+"&t=10&r='"+permisos+"'";
                fetch(urlAEnviar)
                .then(function(response)
                {
                    return response.json();
                })
                .then(function(json)
                {
                    escribeExamenes(json);
                });
            }
            paginas.appendChild(enlacePagina);
        }
    }

    function removeAllChildNodes(parent)
    {
        while(parent.firstChild)
        {
            parent.removeChild(parent.firstChild);
        }
    }

    tabla.appendChild(cabesa);
    tabla.appendChild(corpus);
})