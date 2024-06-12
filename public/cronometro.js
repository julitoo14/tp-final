document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');      //Me traigo el cronometro
    const timeBarFill = document.getElementById('time-bar-fill');   //Me traigo la barra
    let timeLeft = localStorage.getItem('timeLeft') || 20; // Obtén el tiempo restante del almacenamiento local o usa 20 si no hay nada guardado
    const totalTime = 20;
    let partidaId; // Define partidaId en este alcance

    fetch('/GameController/getPartidaId', {
        method: 'GET',
    })
        .then(response => response.text())
        .then(id => {
            // Almacena el ID de la partida en el almacenamiento local
            localStorage.setItem('partidaId', id);
            partidaId = id; // Asigna el ID de la partida a partidaId
        })
        .catch(error => console.error('Error:', error));

    // Actualiza el texto del temporizador inmediatamente
    timerElement.textContent = timeLeft;

    // Reduce el ancho del relleno de la barra de tiempo
    timeBarFill.style.width = ((timeLeft / totalTime) * 100) + '%';
    setTimeout(() => {
        timeBarFill.style.width = '0%';
    }, 0);

    //Actualiza el texto del temporizador cada segundo
    const interval = setInterval(() => {
        timeLeft--;
        localStorage.setItem('timeLeft', timeLeft); // Guarda el tiempo restante en el almacenamiento local
        timerElement.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(interval);
            alert("Tiempo terminado");
            localStorage.removeItem('timeLeft'); // Elimina el tiempo restante del almacenamiento local cuando el tiempo se agota

            fetch('/GameController/timeUp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `partidaId=${partidaId}` // Ahora puedes usar partidaId aquí
            })
                .then(response => {
                    if (response.ok) {
                        // Si la respuesta es exitosa, redirige al usuario a la vista de inicio
                        window.location.href = '/HomeView.mustache';
                    } else {
                        console.error('Error:', response.statusText);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }, 1000);
});