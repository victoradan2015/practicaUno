function validationButton() {
    // Deshabilitar el botón para evitar clics adicionales
    document.getElementById("miBoton").disabled = true;

    // Tu lógica aquí
    // ...
    console.log('ejecuta funcion JS')
    // Habilitar el botón después de que la función haya terminado
    setTimeout(function() {
      document.getElementById("btnEnviar").disabled = false
    }, 1000); // 1000 milisegundos = 1 segundo (ajusta según sea necesario)
}