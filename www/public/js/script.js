function playBuzzerSound() {
    console.log("played");
    var audio = new Audio('/sound/buzzer.mp3');
    audio.play();
    setTimeout(function() {
        document.getElementById("buzzerForm").submit();
    }, 500);
}

function buzzerCooldown() {
    setTimeout(function() {
        document.getElementById("buzzerTop").disabled = false;
    }, 5000);
}