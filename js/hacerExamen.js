window.addEventListener("load",function()
{
    // Hay que meterle también todo el rollo de la paginación, div oculto div visible para las preguntas, etcétera.
    var idExamen = document.getElementById("idSecretoOcultoEscondido").innerHTML;
    var seccion_preguntas = document.getElementById("seccion_preguntas_examen");
    var seccion_enlaces = document.getElementById("paginacion_preguntas_examen");
    var titulo = document.getElementById("titulo_examen");
    var temporizador = document.getElementById("temporizador");

    function startTimer(duracion, adonde)
    {
        var timer = duracion, minutes, seconds;
        setInterval(function()
        {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        adonde.textContent = minutes + ":" + seconds;

        if(--timer < 0) {
            terminarExamen();
        }
        }, 1000);
    }

    var arrayRespuestas = [];

    // Aquí tengo que hacer un fetch
    fetch("traeExamen.php?id="+idExamen)
    .then(function(response)
    {
        return response.text();
    })
    .then(function(response)
    { // A ver cómo hago esto.
        objetoRespuestaJSON = response;
        objetoRespuesta = JSON.parse(response);
        listaPreguntas = objetoRespuesta.preguntas;

        listaPreguntas = desordenarVector(listaPreguntas);

        for(let i = 0; i < listaPreguntas.length; i++)
        {
            dibujaPregunta(listaPreguntas[i]);
        }
        seccion_preguntas.children[0].classList.remove("oculto");
        dibujaEnlacesPregunta(listaPreguntas);

        var duracion = objetoRespuesta.duracion;
        startTimer(duracion*60, temporizador);
    });

    function desordenarVector(listaPreguntas)
    {
        let currentIndex = listaPreguntas.length, randomIndex;

        while(currentIndex != 0)
        {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            [listaPreguntas[currentIndex], listaPreguntas[randomIndex]] = [listaPreguntas[randomIndex], listaPreguntas[currentIndex]];
        }

        return listaPreguntas;
    }

    function dibujaPregunta(pregunta)
    {
        preguntica = document.createElement("article");
        preguntica.id = "pregunta_"+pregunta.pregunta.id;
        recurso = document.createElement("section");
        recurso.classList.add("izquierdo");
        recurso.innerHTML = '<img src="data:image/jpeg;base64,"'+pregunta.pregunta.recurso+' />';
        texto = document.createElement("div");
        texto.classList.add("derecho");

        listaRespuestas = pregunta.respuestas;
        listaRespuestas = desordenarVector(listaRespuestas);

        enunciado = document.createElement("article");
        enunciado.innerHTML = pregunta.pregunta.enunciado;
        texto.appendChild(enunciado);
        respuestas = document.createElement("article");
        for (var i = 0; i < 4; i++)
        {
            respuestaWrapper = document.createElement("label");
            respuestaWrapper.setAttribute("for","respuesta_"+listaRespuestas[i].id);
            respuesta = document.createElement("input");
            respuesta.setAttribute("type","radio");
            respuesta.setAttribute("name","respuestas_"+pregunta.pregunta.id);
            respuesta.setAttribute("id","respuesta_"+listaRespuestas[i].id)
            let id = listaRespuestas[i].id;
            respuestaWrapper.onchange = function()
            {
                var indice = listaPreguntas.indexOf(pregunta);
                arrayRespuestas[indice]=id;
                console.log(arrayRespuestas);
            }
            respuestaWrapper.appendChild(respuesta);
            respuestaWrapper.innerHTML += listaRespuestas[i].enunciado;
            saltolinea = document.createElement("br");
            respuestas.appendChild(respuestaWrapper);
            respuestas.appendChild(saltolinea);
        }
        texto.appendChild(respuestas);

        preguntica.appendChild(recurso);
        preguntica.appendChild(texto);
        seccion_preguntas.appendChild(preguntica);

        preguntica.classList.add("oculto");
    }

    function dibujaEnlacesPregunta(listaPreguntas)
    {
        for(let i = 0; i < listaPreguntas.length; i++)
        {
            var enlacePregunta = document.createElement("a");
            enlacePregunta.classList.add("enlace_pregunta");
            enlacePregunta.onclick = function()
            {
                ocultaTodo();
                seccion_preguntas.children[i].classList.remove("oculto");
                titulo.innerHTML = "Pregunta "+(i+1);
            }
            enlacePregunta.innerHTML = i+1;
            seccion_enlaces.appendChild(enlacePregunta);
        }
    }

    var boton = document.getElementById("botonEnviar");
    boton.onclick = function(ev)
    {
        ev.preventDefault();
        // Comprobar que todas las preguntas están contestadas. ¿Cómo hacemos eso? Aaaann... haber estudiao
        let preguntasContestadas = false;
        if(arrayRespuestas.length == objetoRespuesta.preguntas.length)
        {
            if(confirm("¿Está seguro de querer enviar el examen?"))
            {
                preguntasContestadas = true;
            }
        }
        else if(arrayRespuestas.length < objetoRespuesta.preguntas.length)
        {
            if(confirm("¿Está seguro de querer enviar el examen? Aún quedan preguntas por contestar."))
            {
                preguntasContestadas = true;
            }
        }

        if(preguntasContestadas == true)
        {
            terminarExamen();
        }
    }

    function terminarExamen()
    {
        objetoRespuesta.preguntas = listaPreguntas;
        for(let i = 0; i < objetoRespuesta.preguntas.length; i++)
        {
            objetoRespuesta.preguntas[i].respuestaMarcada = arrayRespuestas[i];
        }
        var argonauta = JSON.stringify(objetoRespuesta);

        // Y ahora hay que hacer un fetch para mandar al argonauta

        fetch("mandaExamen.php",
        {
            method: 'POST',
            body: argonauta
        })
        .then(function(response)
        {
            return response.text();
        })
        .then(function(response)
        {
            console.log(response);
        });
        window.location.replace("examenTerminado.php");
    }

    function ocultaTodo()
    {
        for (let i = 0; i < seccion_preguntas.children.length; i++)
        {
            seccion_preguntas.children[i].classList.add("oculto");
        }
    }



})