window.addEventListener("load",function()
{
    // TODO Hay que añadir la cuarta columna de acciones
    var tabla = document.getElementById("tabla");

    var cabesa = document.createElement("thead");
    var corpus = document.createElement("tbody");
    
    var paginas = document.getElementById("paginas");

    // Esto tengo que mirármelo.
    var p = 1;
    var urlAEnviar = "traePreguntasPaginadas.php?p="+p+"&t=10";
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
        let arrayoMcQueen = ["id","enunciado","temática"];
        for (let i = 0; i < arrayoMcQueen.length; i++)
        {
            let columna = document.createElement("th");
            columna.innerHTML = arrayoMcQueen[i];
            cabecera.appendChild(columna);
        }
        cabesa.appendChild(cabecera);
    }

    function escribePreguntas(json)
    {
        let arrayoMcQueen = ["id","enunciado","tematica"];
        for (let i = 0; i < Object.keys(json).length-1; i++)
        {
            let fila = document.createElement("tr");
            for (let j = 0; j < arrayoMcQueen.length; j++)
            {
                let contenido = document.createElement("td");
                contenido.innerHTML = json[i][arrayoMcQueen[j]];
                fila.appendChild(contenido);
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
                urlAEnviar = "traePreguntasPaginadas.php?p="+i+"&t=10";
                fetch(urlAEnviar)
                .then(function(response)
                {
                    return response.json();
                })
                .then(function(json)
                {
                    escribePreguntas(json);
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