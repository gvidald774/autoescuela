window.addEventListener("load",function()
{
    var idExamen = document.getElementById("idSecretoOcultoEscondido").innerHTML;
    var seccion_preguntas = document.getElementById("seccion_preguntas_examen");
    var seccion_enlaces = document.getElementById("paginacion_preguntas_examen");
    var titulo = document.getElementById("titulo_examen");

    fetch("traeExamenRealizado.php?id="+idExamen)
    .then(function(response)
    {
        return response.text();
    })
    .then(function(response)
    {
        objetoRespuestaJSON = response;
        objetoRespuesta = JSON.parse(response);
        
        listaPreguntas = objetoRespuesta.preguntas;

        for (let i = 0; i < listaPreguntas.length; i++)
        {
            dibujaPregunta(listaPreguntas[i]);
        }
        seccion_preguntas.children[0].classList.remove("oculto");
        dibujaEnlacesPregunta(listaPreguntas);
    });

    function dibujaPregunta(pregunta)
    {
        preguntica = document.createElement("article");
        preguntica.id = "pregunta_"+pregunta.pregunta.id;
        if(respuestaCorrecta(pregunta.pregunta.respuestaCorrecta, pregunta.respuestaMarcada))
        {
            preguntica.classList.add("preguntaCorrecta");
        }
        else if(pregunta.respuestaMarcada == null)
        {
            preguntica.classList.add("preguntaSinContestar");
        }
        else if(!respuestaCorrecta(pregunta.pregunta.respuestaCorrecta, pregunta.respuestaMarcada))
        {
            preguntica.classList.add("preguntaIncorrecta");
        }
        if(pregunta.pregunta.recurso != null)
        {
            var recurso = document.createElement("section");
            recurso.id = "recurso_pregunta_examen";
            recurso.classList.add("izquierdo");
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

        var listaRespuestas = pregunta.respuestas;
        var enunciado = document.createElement("article");
        enunciado.classList.add("enunciado_pregunta");
        enunciado.innerHTML = pregunta.pregunta.enunciado;
        texto.appendChild(enunciado);
        respuestas = document.createElement("article");
        for (var i = 0; i < 4; i++)
        {
            var respuestaWrapper = document.createElement("label");
            respuestaWrapper.setAttribute("for", "respuesta_"+listaRespuestas[i].id);
            var respuesta = document.createElement("input");
            respuesta.setAttribute("type","radio");
            respuesta.setAttribute("name","respuestas_"+pregunta.pregunta.id);
            respuesta.setAttribute("id","respuesta_"+listaRespuestas[i].id);
            if(pregunta.respuestaMarcada == pregunta.respuestas[i].id)
            {
                respuesta.setAttribute("checked","checked");
            }
            respuesta.disabled = true;
            respuestaWrapper.appendChild(respuesta);
            respuestaWrapper.innerHTML += listaRespuestas[i].enunciado;

            if(respuesta.getAttribute("checked") == "checked")
            {
                if(preguntica.classList.contains("preguntaCorrecta"))
                {
                    respuestaWrapper.innerHTML += "<span class='textoCorrecta '>Respuesta correcta</span>";
                }
                else if(preguntica.classList.contains("preguntaIncorrecta"))
                {
                    respuestaWrapper.innerHTML += "<span class='textoIncorrecta'>Respuesta incorrecta</span>";
                }
            }
            else if(pregunta.pregunta.respuestaCorrecta == pregunta.respuestas[i].id)
            {
                respuestaWrapper.innerHTML += "<span class='textoCorrecta'>Respuesta correcta</span>";
            }

            saltolinea = document.createElement("br");
            respuestas.appendChild(respuestaWrapper);
            respuestas.appendChild(saltolinea);
        }
        texto.appendChild(respuestas);

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
            if(seccion_preguntas.children[i].classList.contains("preguntaCorrecta"))
            {
                enlacePregunta.classList.add("enlace_correcta");
            }
            else if(seccion_preguntas.children[i].classList.contains("preguntaIncorrecta"))
            {
                enlacePregunta.classList.add("enlace_incorrecta");
            }
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

    function respuestaCorrecta(respuestaCorrecta, respuestaMarcada)
    {
        if(respuestaCorrecta == respuestaMarcada)
        {
            return true;
        }
        else return false;
    }

    function ocultaTodo()
    {
        for (let i = 0; i < seccion_preguntas.children.length; i++)
        {
            seccion_preguntas.children[i].classList.add("oculto");
        }
    }
})