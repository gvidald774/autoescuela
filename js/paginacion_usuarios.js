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
        escribeUsuarios(json);
        escribePaginas(json);
    });

    function escribeCabeceras(json)
    {
        let cabecera = document.createElement("tr");
        let arrayoMcQueen = ["Nombre", "Rol", "Localidad", "Exámenes realizados", "opciones"];
        for (let i = 0; i < arrayoMcQueen.length; i++)
        {
            let columna = document.createElement("th");
            columna.innerHTML = arrayoMcQueen[i];
            cabecera.appendChild(columna);
        }
        cabesa.appendChild(cabecera);
    }

    function escribeUsuarios(json)
    {
        let arrayoMcQueen = ["nombre","rol","localidad","n_examenes","opciones"];
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
                    enlaceEditar.href = "datosUsuario.php?id="+id;
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
                        if(confirm("¿Está seguro de querer borrar este usuario?"))
                        {
                            let idBorra = contenido.parentElement.firstElementChild.innerHTML;
                            borraDato(idBorra);
                        }
                    }
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
        for (let i = 1; i <= json.npag; i++)
        {
            enlacePagina = document.createElement("a");
            enlacePagina.innerHTML = i;
            enlacePagina.href = "#";
            enlacePagina.style = "margin: 10px; ";
            enlacePagina.onclick = function()
            {
                refresh(i);
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

    function refresh(p)
    {
        removeAllChildNodes(corpus);
        urlAEnviar = "traeUsuariosPaginados.php?p="+p+"&t=10";
        fetch(urlAEnviar)
        .then(function(response)
        {
            return response.json();
        })
        .then(function(json)
        {
            escribeUsuarios(json);
        });
    }

    function borraDato(idBorra)
    {
        var formData = new FormData();
        formData.append('tabla', 'pregunta');
        formData.append('id',idBorra);
        fetch("borradoFila.php",
        {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(response => alert(response));
        refresh(1);
    }

    tabla.appendChild(cabesa);
    tabla.appendChild(corpus);
})