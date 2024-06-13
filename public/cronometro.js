document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');
    const timeBarFill = document.getElementById('time-bar-fill');
    let timeLeft = localStorage.getItem('timeLeft') || 20;
    const totalTime = 20;
    let partidaId;

    fetch('/Game/getPartidaId', {
        method: 'GET',
    })
        .then(response => response.text())
        .then(id => {
            localStorage.setItem('partidaId', id);
            partidaId = id;
        })
        .catch(error => console.error('Error:', error));

    timerElement.textContent = timeLeft;
    timeBarFill.style.width = ((timeLeft / totalTime) * 100) + '%';

    setTimeout(() => {
        timeBarFill.style.width = '0%';
    }, 0);

    const interval = setInterval(() => {
        timeLeft--;
        localStorage.setItem('timeLeft', timeLeft);
        timerElement.textContent = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(interval);
            $('#timeUpModal').modal('show'); // Muestra el modal
            localStorage.removeItem('timeLeft');
        }
    }, 1000);

});