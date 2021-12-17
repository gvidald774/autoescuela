window.addEventListener("load",function()
{
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

        if(timer < 300)
        {
            adonde.style="color: red";
        }

        if(--timer < 0) {
            terminarExamen();
        }
        }, 1000);
    }

    var arrayRespuestas = [];

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
        
        if(pregunta.pregunta.recurso != null)
        {
            var recurso = document.createElement("section");
            recurso.id = "recurso_pregunta_examen";
            recurso.classList.add("izquierdo");
            recurso.classList.add("examen_imagen");
            if (pregunta.pregunta.type == "video/mp4")
            {
                recurso.innerHTML = '<video controls><source src="data:video/mp4;base64,'+pregunta.pregunta.recurso+'" ></video>';
            }
            else {
                recurso.innerHTML = '<img src="data:image/jpeg;base64,'+pregunta.pregunta.recurso+'" />';
            }

            preguntica.appendChild(recurso);
        }

        texto = document.createElement("div");
        texto.classList.add("izquierdo");
        texto.classList.add("texto_examen");

        listaRespuestas = pregunta.respuestas;
        listaRespuestas = desordenarVector(listaRespuestas);

        var enunciado = document.createElement("article");
        enunciado.classList.add("enunciado_pregunta");
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
                seccion_enlaces.children[indice].classList.add("pregunta_contestada");
            }
            respuestaWrapper.appendChild(respuesta);
            respuestaWrapper.innerHTML += listaRespuestas[i].enunciado;
            saltolinea = document.createElement("br");
            respuestas.appendChild(respuestaWrapper);
            respuestas.appendChild(saltolinea);
        }
        var borrado = document.createElement("input");
        borrado.setAttribute("type","button");
        borrado.setAttribute("value","Borrar respuesta");
        borrado.classList.add("ninguno");
        borrado.setAttribute("id","botonBorrar");
        borrado.onclick = function(ev)
        {
            ev.preventDefault();
            
            radios = this.parentElement.getElementsByTagName("input");
            for(let i = 0; i < radios.length; i++)
            {
                radios[i].checked = false;
            }

            var indice = listaPreguntas.indexOf(pregunta);
            delete arrayRespuestas[indice];
            console.log(arrayRespuestas);
            seccion_enlaces.children[indice].classList.remove("pregunta_contestada");

        }
        texto.appendChild(respuestas);
        
        texto.appendChild(borrado);
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
            window.location.replace("examenTerminado.php?id="+response);
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