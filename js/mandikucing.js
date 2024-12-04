const catImage = document.getElementById('catImage');

        function changeToSabun() {
        catImage.src = "img/kucing_sabun.png";
    }

    document.getElementById('showSabunButton').addEventListener('click', changeToSabun);
