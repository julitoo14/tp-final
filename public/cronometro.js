document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');
    const timeBarFill = document.getElementById('time-bar-fill');
    const totalTime = 20;

    const preguntaId = document.querySelector('input[name="preguntaId"]').value;

    // Recupera el último ID de pregunta y el tiempo restante del localStorage
    const lastPreguntaId = localStorage.getItem('preguntaId');
    let timeLeft = localStorage.getItem('timeLeft');

    if (lastPreguntaId === preguntaId) {
        // Si es la misma pregunta, continuar con el tiempo restante
        timeLeft = timeLeft ? parseInt(timeLeft, 10) : totalTime;
    } else {
        // Si es una nueva pregunta, reiniciar el temporizador
        timeLeft = totalTime;
    }

    // Almacenar el ID de la pregunta actual en localStorage
    localStorage.setItem('preguntaId', preguntaId);


    const intervalId = setInterval(() => {
        // Verificar si el modal de "Perdiste" está visible
        if ($('#perdisteModal').hasClass('show')) {
            clearInterval(intervalId);
            localStorage.removeItem('timeLeft');
            localStorage.removeItem('preguntaId');
            return;
        }

        timeLeft--;
        localStorage.setItem('timeLeft', timeLeft);
        timerElement.textContent = timeLeft;

        // Calculate the width percentage of the time bar
        const fillWidth = (timeLeft / totalTime) * 100;
        timeBarFill.style.width = `${fillWidth}%`;

        if (timeLeft <= 0) {
            clearInterval(intervalId);
            localStorage.removeItem('timeLeft');
            localStorage.removeItem('preguntaId');
            $('#timeUpModal').modal('show');
        }
    }, 1000);

    document.getElementById('acceptButton').addEventListener('click', function() {
        // Redirigir a la lógica de tiempo agotado
        window.location.href = "/index.php?controller=Game&action=timeUp";
    });

    window.addEventListener('beforeunload', function() {
        // Guardar el tiempo restante antes de recargar o cerrar la página
        localStorage.setItem('timeLeft', timeLeft);
    });
});
