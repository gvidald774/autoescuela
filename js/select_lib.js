/**
 * Esta es una librería con distintas funciones para usar en un
 * HTMLSelectElement (un select de toda la vida).
 */

/**
 * Operación que permite añadir todos los elementos de un select dado (select) a otro desde el que se llama.
 * @param {*} select El select del cual se quieren sacar las opciones.
 */
 HTMLSelectElement.prototype.addAll = function(select)
 {
     // Mientras queden options en el select...
     while(select.children.length>0)
     {
         this.appendChild(select.children[0]); // Se añaden al otro.
     }
     // Terminamos ordenando
     this.ordenar();
     // Y deseleccionando (por elegancia).
     for (let i = 0; i < this.children.length; i++)
     {
         this.children[i].selected = false;
     }
 }
 
 /**
  * Operación que permite añadir los elementos seleccionados de un select dado a otro desde el que se llama.
  * @param {*} select El select del cual se quieren sacar las opciones.
  */
 HTMLSelectElement.prototype.addUno = function(select)
 {
     // Mientras queden opciones seleccionadas...
     while(select.selectedOptions.length > 0)
     {
         this.appendChild(select.selectedOptions[0]); // Se añaden al otro.
     }
     // Terminamos ordenando
     this.ordenar();
     // Y deseleccionando (por elegancia).
     for (let i = 0; i < this.children.length; i++)
     {
         this.children[i].selected = false;
     }
 }
 
 /**
  * Función que ordena todos los elementos del select.
  */
 HTMLSelectElement.prototype.ordenar = function()
 {
     // Creamos un vector con las opciones del select, que es lo que ordenaremos.
     let vectorcito = [].slice.call(this.children);
     vectorcito.sort(function(a,b){return a.innerHTML.localeCompare(b.innerHTML)}); // Comparamos según el contenido.
     // Reorganizamos las opciones con respecto al contenido del vector.
     for (let i = 0; i < vectorcito.length; i++)
     {
         this.appendChild(vectorcito[i]);
     }
 }
 
 /**
  * Función que devuelve un texto JSON del contenido del select.
  */
 HTMLSelectElement.prototype.parseJSON = function(compartido = false, option_value = true, option_content = true)
 {
     var vector = [];
     for (let i = 0; i < this.children.length; i++)
     {
         vector.push({"value":this.children[i].value,"texto":this.children[i].innerHTML});
     }
 
     var jotason = JSON.stringify(vector);
 
     return jotason;
 }