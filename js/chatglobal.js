document.getElementById("bukaChat").addEventListener("click", () => {
    sessionStorage.setItem("previousPage", window.location.href);
    window.location.href = "chatGlobal.php";
});

function closeChat() {
    const previousPage = sessionStorage.getItem("previousPage");
    if (previousPage) {
        window.location.href = previousPage; 
    } else {
        window.location.href = "beranda.html";
    }
}