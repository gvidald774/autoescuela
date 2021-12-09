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
        let arrayoMcQueen = ["id","enunciado","temática","opciones"];
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
        let arrayoMcQueen = ["id","enunciado","tematica","opciones"];
        for (let i = 0; i < Object.keys(json).length-1; i++)
        {
            let fila = document.createElement("tr");
            for (let j = 0; j < arrayoMcQueen.length; j++)
            {
                if(arrayoMcQueen[j] == "opciones")
                {
                    let contenido = document.createElement("td");
                    contenido.style = "font-size: small";
                    let enlaceEditar = document.createElement("a");
                    let id = json[i]["id"];
                    enlaceEditar.href = "altaPregunta.php?id="+id;
                    enlaceEditar.innerHTML = "Editar";
                    let enlaceDesactivar = document.createElement("a");
                    enlaceDesactivar.innerHTML = " Desactivar";
                    enlaceDesactivar.href = "#";
                    let enlaceBorrar = document.createElement("a");
                    enlaceBorrar.innerHTML = " Borrar";
                    enlaceBorrar.href = "#";
                    contenido.appendChild(enlaceEditar);
                    contenido.appendChild(enlaceDesactivar);
                    contenido.appendChild(enlaceBorrar);
                    fila.appendChild(contenido);

                    enlaceBorrar.onclick = function()
                    {
                        if(confirm("¿Está seguro de querer borrar esta pregunta? (Se borrarán también las respuestas y recurso asociados a la pregunta)"))
                        {
                            let idBorra = contenido.parentElement.firstElementChild.innerHTML;
                            // borraDato("pregunta",idBorra); // No señor, ahora hay que hacer una cosa espectacular y atómica para el borrado de la pregunta.
                            alert("NO SE HA BORRADO "+idBorra+" PORQUE ES MU COMPLICAO Y YO NO SE TOY CHIQUITO");
                        }
                    }
                }
                else
                {
                    let contenido = document.createElement("td");
                    contenido.innerHTML = json[i][arrayoMcQueen[j]];
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