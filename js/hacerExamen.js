window.addEventListener("load",function()
{
    // Hay que meterle también todo el rollo de la paginación, div oculto div visible para las preguntas, etcétera.
    var formularino = document.getElementById("examen");
    var idExamen = document.getElementById("idSecretoOcultoEscondido").innerHTML;
    var seccion_preguntas = document.getElementById("seccion_preguntas_examen");
    var seccion_enlaces = document.getElementById("paginacion_preguntas_examen");
    var titulo = document.getElementById("titulo_examen");

    // Aquí tengo que hacer un fetch
    fetch("traeExamen.php?id="+idExamen)
    .then(function(response)
    {
        return response.text();
    })
    .then(function(response)
    { // A ver cómo hago esto.
        objetoRespuesta = JSON.parse(response);
        listaPreguntas = objetoRespuesta.preguntas;

        listaPreguntas = desordenarVector(listaPreguntas);

        for(let i = 0; i < listaPreguntas.length; i++)
        {
            dibujaPregunta(i, listaPreguntas[i]);
        }
        seccion_preguntas.children[0].classList.remove("oculto");
        dibujaEnlacesPregunta(listaPreguntas);
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

    function dibujaPregunta(numeroPregunta, pregunta)
    {
        preguntica = document.createElement("article");
        preguntica.id = "pregunta_"+pregunta.pregunta.id;
        recurso = document.createElement("section");
        recurso.classList.add("izquierdo");
        texto = document.createElement("texto");
        texto.classList.add("derecho");

        enunciado = document.createElement("article");
        enunciado.innerHTML = pregunta.pregunta.enunciado;
        texto.appendChild(enunciado);
        respuestas = document.createElement("article");
        for (var i = 0; i < 4; i++)
        {
            respuestaWrapper = document.createElement("label");
            respuestaWrapper.setAttribute("for","respuesta_"+pregunta.respuestas[i].id);
            respuesta = document.createElement("input");
            respuesta.setAttribute("type","radio");
            respuesta.setAttribute("name","respuestas_"+pregunta.pregunta.id);
            respuesta.setAttribute("id","respuesta_"+pregunta.respuestas[i].id)
            respuestaWrapper.appendChild(respuesta);
            respuestaWrapper.innerHTML += pregunta.respuestas[i].enunciado;
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
        
        // Aquí hay que leer chorrocientos datos
        

    }

    function ocultaTodo()
    {
        for (let i = 0; i < seccion_preguntas.children.length; i++)
        {
            seccion_preguntas.children[i].classList.add("oculto");
        }
    }



})