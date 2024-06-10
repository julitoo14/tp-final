
document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');      //Me traigo el cronometro
    const timeBarFill = document.getElementById('time-bar-fill');   //Me traigo la barra
    let timeLeft = 20;
    const totalTime = 20;

    // Reduce el ancho del relleno de la barra de tiempo

    timeBarFill.style.width = '100%';
    setTimeout(() => {
        timeBarFill.style.width = '0%';
    }, 0);

    //Actualiza el texto del temporizador cada segundo

    const interval = setInterval(() => {
        timeLeft--;
        timerElement.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(interval);
            alert("Tiempo terminado");
        }
    }, 1000);
});