document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('timer');
    const timeBarFill = document.getElementById('time-bar-fill');
    let timeLeft = 20;
    const totalTime = 20;

    const intervalId = setInterval(() => {
        timeLeft--;
        timerElement.textContent = timeLeft;

        // Calculate the width percentage of the time bar
        const fillWidth = (timeLeft / totalTime) * 100;
        timeBarFill.style.width = `${fillWidth}%`;

        if (timeLeft <= 0) {
            clearInterval(intervalId);
            $('#timeUpModal').modal('show');
        }
    }, 1000);

    document.getElementById('acceptButton').addEventListener('click', function() {
        // Redirection to time up logic
        window.location.href = "/index.php?controller=Game&action=timeUp";
    });
});
