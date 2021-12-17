window.addEventListener("load",function()
{
    formularino = document.getElementsByTagName("form")[0];
    boton = document.getElementById("boton_nuevoUsuario");

    formularino["password1"].onblur = function()
    {
        enableButton(boton);
        if(!coincidenDosTextos(formularino["password1"].value, formularino["password2"].value))
        {
            document.getElementById("error_password").innerHTML = "Las contraseñas no coinciden.";
            disableButton(boton);
        }
        else if(coincidenDosTextos(formularino["password1"].value, formularino["password2"].value))
        {
            document.getElementById("error_password").innerHTML = "";
        }
    }
    formularino["password2"].onblur = function()
    {
        enableButton(boton);
        if(!coincidenDosTextos(formularino["password1"].value, formularino["password2"].value))
        {
            document.getElementById("error_password").innerHTML = "Las contraseñas no coinciden.";
            disableButton(boton);
        }
        else if(coincidenDosTextos(formularino["password1"].value, formularino["password2"].value))
        {
            document.getElementById("error_password").innerHTML = "";
        }
    }

    formularino["f_nac"].onblur = function()
    {
        enableButton(boton);
        if(!compruebaFechaNacimiento(formularino["f_nac"].value))
        {
            document.getElementById("error_fecha").innerHTML = "La fecha debe ser válida (Mayor de 18 y después de 1900)";
            disableButton(boton);
        }
        else if(compruebaFechaNacimiento(formularino["f_nac"].value))
        {
            document.getElementById("error_fecha").innerHTML = "";
        }
    }
})