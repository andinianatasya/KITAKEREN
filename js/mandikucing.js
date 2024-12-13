(() => {
    const showSabunButton = document.getElementById("showSabunButton");
    const sabunOverlay = document.getElementById("sabunOverlay");

    showSabunButton.addEventListener("click", function() {
        sabunOverlay.classList.toggle("hidden");
    });
})();