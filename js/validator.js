function esCorreoIndividual(texto)
{
    let regexp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

    let resultado = false;
    if(regexp.test(texto))
    {
        resultado = true;
    }
    
    return resultado;
}

function compruebaFechaNacimiento(fecha)
{
    var inicio = new Date("01/01/1900").getTime();
    var fin = new Date();
    fin.setMonth(fin.getMonth() - (12*18));
    fin = fin.getTime();

    fechaNac = Date.parse(fecha);

    var resultado = false;
    if(fechaNac >= inicio && fechaNac <= fin)
    {
        resultado = true;
    }

    return resultado;
}

function coincidenDosTextos(texto1, texto2)
{
    coinciden = false;
    if(texto1 == texto2)
    {
        coinciden = true;
    }
    return coinciden;
}