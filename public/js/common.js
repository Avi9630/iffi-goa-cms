setTimeout(function () {
    let flash = document.getElementById("flash-message");
    if (flash) {
        flash.style.transition = "opacity 1s ease-out";
        flash.style.opacity = "0";
        setTimeout(function () {
            flash.style.display = "none";
        }, 2000);
    }
}, 1000);
