window.addEventListener("load",function()
{
    // Hay que meterle también todo el rollo de la paginación, div oculto div visible para las preguntas, etcétera.
    formularino = document.getElementById("examen");
    idExamen = document.getElementById("idSecretoOcultoEscondido").innerHTML;

    // Aquí tengo que hacer un fetch
    fetch("traeExamen.php?id="+idExamen)
    .then(response => alert("Fuck you"))
    .then(response => alert("Fuck off"));

    function dibujaPregunta(numeroPregunta, pregunta)
    {
        preguntica = document.createElement("article");
        preguntica.id = "pregunta_"+pregunta.id;
        recurso = document.createElement("section");
        recurso.classList.add("izquierdo");
        texto = document.createElement("texto");
        texto.classList.add("derecho");

        enunciado = document.createElement("article");
        enunciado.innerHTML = pregunta.enunciado;
        texto.appendChild(enunciado);
        respuestas = document.createElement("article");
        for (var i = 0; i < 4; i++)
        {
            respuestaWrapper = document.createElement("label");
            respuestaWrapper.setAttribute("for","respuesta_"+pregunta.respuestas[i].id);
            respuesta = document.createElement("input");
            respuesta.setAttribute("type","radio");
            respuesta.setAttribute("name","respuestas_"+pregunta.id);
            respuesta.setAttribute("id","respuesta_"+pregunta.respuestas[i].id)
            respuestaWrapper.appendChild(respuestaWrapper);
            respuestas.appendChild(respuestaWrapper);
        }
        texto.appendChild(respuestas);

        preguntica.appendChild(recurso);
        preguntica.appendChild(texto);
        examen.appendChild(preguntica);
    }

})