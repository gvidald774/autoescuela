window.addEventListener("load",function()
{
    var preguntas = document.getElementById("bancoPreguntas");
    var seleccionadas = document.getElementById("preguntasSeleccionadas");
    var boton = document.getElementById("botonEnviar");

    var preguntasJSON = document.getElementById("bancoPreguntas_JSON").innerHTML;
    var seleccionadasJSON = document.getElementById("seleccionadas_JSON").innerHTML;

    var pregunticas = JSON.parse(preguntasJSON);
    var seleccionadicas = JSON.parse(seleccionadasJSON);

    var formularino = document.getElementsByTagName("form")[0];

    if(seleccionadicas.length == 0)
    {
        fetch("traePreguntas.php")
        .then(function(response)
        {
            return response.json();
        })
        .then(function(json)
        {
            creaPreguntas(json,preguntas);
        });
    }
    else
    {
        creaPreguntas(pregunticas, preguntas);
        creaPreguntas(seleccionadicas, seleccionadas);
    }

    // else pues ya tendríamos el json y lo hacemos directamente
    
    function creaPreguntas(json, adonde)
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
            adonde.appendChild(div);
            dragAndDrop(div);
        }
    }

    const filtroBancoPreguntas = document.getElementById("filtroBancoPreguntas");
    filtroBancoPreguntas.onkeyup = function()
    {
        const divs = preguntas.getElementsByClassName("pregunta");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtroBancoPreguntas.value)<0)
            {
                divs[i].classList.add("oculto");
            }
            else
            {
                divs[i].classList.remove("oculto");
            }
        }
    }

    const botonBancoPreguntas = document.getElementById("botonFiltroBancoPreguntas");
    botonBancoPreguntas.onclick = function()
    {
        const divs = preguntas.getElementsByClassName("pregunta");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtroBancoPreguntas.value)<0)
            {
                divs[i].classList.add("oculto");
            }
            else
            {
                divs[i].classList.remove("oculto");
            }
        }
    }

    const filtroPreguntasSeleccionadas = document.getElementById("filtroPreguntasSeleccionadas");
    filtroPreguntasSeleccionadas.onkeyup = function()
    {
        const divs = seleccionadas.getElementsByClassName("pregunta");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtroPreguntasSeleccionadas.value)<0)
            {
                divs[i].classList.add("oculto");
            }
            else
            {
                divs[i].classList.remove("oculto");
            }
        }
    }

    const botonFiltroPreguntasSeleccionadas = document.getElementById("botonFiltroPreguntasSeleccionadas");
    botonFiltroPreguntasSeleccionadas.onclick = function()
    {
        const divs = seleccionadas.getElementsByClassName("pregunta");
        for(let i = 0; i < divs.length; i++)
        {
            divs[i].classList.remove("marcado");
            if(divs[i].innerHTML.indexOf(filtroPreguntasSeleccionadas.value)<0)
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
        const marcados = preguntas.getElementsByClassName("marcado");
        for (let j = marcados.length-1; j >= 0; j--)
        {
            seleccionadas.appendChild(marcados[j]);
        }
        ev.stopPropagation();
        seleccionadas.sort(function(a, b) {
            var compA = a.getAttribute('id').toUpperCase();
            var compB = b.getAttribute('id').toUpperCase();
            return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
        });
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
        preguntas.sort(function(a, b) {
            var compA = a.getAttribute('id').toUpperCase();
            var compB = b.getAttribute('id').toUpperCase();
            return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
        });
    }

    // Validaciones

    formularino["n_preg"].onblur = function()
    {
        enableButton(boton);
        if(!positivo(formularino["n_preg"].value))
        {
            document.getElementById("error_npreg").innerHTML = "El nº de preguntas ha de ser mayor que 0."
            disableButton(boton);
        }
    }

    formularino["horas"].onblur = function()
    {
        enableButton(boton);
        document.getElementById("error_duracion").innerHTML = "";
        var duracionTotal = parseInt(formularino["horas"].value*60)+parseInt(formularino["minutos"].value);
        if(!positivo(duracionTotal))
        {
            document.getElementById("error_duracion").innerHTML = "La duración ha de ser superior a 0.";
            disableButton(boton);
        }
    }

    formularino["minutos"].onblur = function()
    {
        enableButton(boton);
        document.getElementById("error_duracion").innerHTML = "";
        var duracionTotal = parseInt(formularino["horas"].value*60)+parseInt(formularino["minutos"].value);
        if(!positivo(duracionTotal))
        {
            document.getElementById("error_duracion").innerHTML = "La duración ha de ser superior a 0.";
            disableButton(boton);
        }
    }

    formularino["minutos"].onchange = function()
    {
        if(formularino["minutos"].value >= 60)
        {
            formularino["minutos"].value = 0;
            formularino["horas"].value++;
        }
    }

    boton.onclick = function(ev)
    {
        ev.preventDefault();
        // Y aquí es donde empezamos a jugar. Tenemos que hacer los arrays teniendo en cuenta el modelo de examen y tal y pascual.
        // Ah y también la capacidad de leer lo que se viene si es un JSON o algo.
        var duracion = parseInt(formularino["horas"].value)*60 + parseInt(formularino["minutos"].value);

        var standardJSON_Object = new Object();
        standardJSON_Object.codigoExamen = formularino["codigoExamen"].value;
        standardJSON_Object.enunciado = formularino["enunciado"].value;
        standardJSON_Object.numPreguntas = parseInt(formularino["n_preg"].value);
        standardJSON_Object.duracion = parseInt(duracion);

        var preguntas_sin_incluir = [];
        for(let i = 0; i < bancoPreguntas.children.length; i++)
        {
            var objetoPregunta = new Object();
            var idPregunta = bancoPreguntas.children[i].id;
            objetoPregunta.id = idPregunta.split("_")[1];
            objetoPregunta.tematica = bancoPreguntas.children[i].firstElementChild.innerHTML;
            objetoPregunta.enunciado = bancoPreguntas.children[i].lastElementChild.innerHTML;
            // objetoPregunta.recurso que lo dejamos para luego

            preguntas_sin_incluir.push(objetoPregunta);
        }

        standardJSON_Object.bancoPreguntas = preguntas_sin_incluir;
        
        var preguntasIncluidas = [];
        for(let i = 0; i < seleccionadas.children.length; i++)
        {
            let stringID = seleccionadas.children[i].id;
            preguntasIncluidas.push(stringID.split("_")[1]);
        }

        if(standardJSON_Object.numPreguntas == preguntasIncluidas.length)
        {
            standardJSON_Object.preguntasIncluidas = preguntasIncluidas;

            var cadena_json = JSON.stringify(standardJSON_Object);

            // Y ahora hacemos un fetch para crear el examen propiamente dicho (?)
            fetch("enviaExamen.php",
            {
                method: 'post',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'Content-Type': 'application/json'
                },

                body: cadena_json

            })
            .then((response) =>
            {
                return response.text();
            })
            .then(response => 
            {
                console.log(response);
                window.location.href = "examenInsertado.php";
            });
        }
        else
        {
            alert("ERROR: El número de preguntas no coincide con las preguntas incluidas.");
        }
    }
});