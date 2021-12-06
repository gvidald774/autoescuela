window.addEventListener("load",function()
{
    var preguntas = document.getElementById("bancoPreguntas");
    var seleccionadas = document.getElementById("preguntasSeleccionadas");
    
    fetch("traePreguntas.php")
    .then(function(response)
    {
        return response.json();
    })
    .then(function(json)
    {
        creaPreguntas(json);
    });
    
    function creaPreguntas(json)
    {
        // Hay que añadirle un id único a cada div de pregunta por cierto
        for(let i = 0; i < json.length; i++)
        {
            let div = document.createElement("div");
            div.id = "pregunta_"+json[i].id;
            div.className = "pregunta";
            let divTema = document.createElement("div");
            divTema.className = "pregunta-tema";
            divTema.innerHTML = json[i].tematica;
            let divEnunciado = document.createElement("div");
            divEnunciado.className = "pregunta-enunciado";
            divEnunciado.innerHTML = json[i].enunciado;
            div.appendChild(divTema);
            div.appendChild(divEnunciado);
            div.draggable = "true";
            document.getElementById("bancoPreguntas").appendChild(div);
            dragAndDrop(div);
        }
    }

    const filtro = document.getElementById("filtro");
    filtro.onkeyup = function()
    {
        const divs = preguntas.getElementsByTagName("div");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtro.value)<0)
            {
                divs[i].classList.add("oculto");
            }
            else
            {
                divs[i].classList.remove("oculto");
            }
        }
    }

    const boton = document.getElementById("botonFiltro");
    boton.onclick = function()
    {
        const divs = preguntas.getElementsByTagName("div");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtro.value)<0)
            {
                divs[i].classList.add("oculto");
            }
            else
            {
                divs[i].classList.remove("oculto");
            }
        }
    }

    function dragAndDrop(div)
    {
        div.ondragstart = function(ev)
        {
            ev.dataTransfer.setData("text",ev.target.id);
        }
        div.ondragover = function(ev)
        {
            ev.preventDefault();
        }
        div.onclick = function()
        {
            this.classList.toggle("marcado");
        }
    }

    
    seleccionadas.ondragover = function(ev)
    {
        ev.preventDefault();
    }

    preguntas.ondragover = function(ev)
    {
        ev.preventDefault();
    }

    seleccionadas.ondrop = function(ev)
    {
        ev.preventDefault();
        const id=ev.dataTransfer.getData("text");
        this.appendChild(document.getElementById(id));
        const marcados = preguntas.getElementsByTagName("marcado");
        for (let j = marcados.length-1; j >= 0; j--)
        {
            seleccionadas.appendChild(marcados[j]);
        }
        ev.stopPropagation();
    }

    preguntas.ondrop = function(ev)
    {
        ev.preventDefault();
        const id = ev.dataTransfer.getData("text");
        this.appendChild(document.getElementById(id));
        const marcados = seleccionadas.getElementsByClassName("marcado");
        for (let j = marcados.length-1; j>=0; j--)
        {
            preguntas.appendChild(marcados[j]);
        }
        ev.stopPropagation();
    }
});