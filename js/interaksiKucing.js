const suaraMeong = document.getElementById("meong");
const catImage = document.getElementById("catImage");
const sabunImage = document.querySelector('img[alt="sabun"]');
const showerImage = document.querySelector('img[alt="shower"]');

catImage.addEventListener('click', function() {
    if (suaraMeong) {
        suaraMeong.play()
            .then(() => console.log('Memutar audio'))
            .catch(error => console.error('Gagal memutar audio:', error));
    } else {
        console.error('Audio tidak ditemukan');
    }
});


function changeToSabun() {
    catImage.src = "img/kucing_sabun.png";
}

function changeToShower() {
    catImage.src = "img/kucing_basah.png";
}

sabunImage.addEventListener("click", changeToSabun);
showerImage.addEventListener("click", changeToShower);

sabunImage.addEventListener("click", changeCatImage);
showerImage.addEventListener("click", changeCatImage);

    const buttons = document.querySelectorAll('.makan');
    buttons.forEach(button => {
        const id_produk = parseInt(this.getAttribute('konsumsi'));
        console.log("Button clicked, id_produk: " + id_produk);
        button.addEventListener('click', () => {
           
            fetch('konsumsi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_produk: id_produk })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); 

                
                if (data.status === 'sukses' && data.message.includes("dihapus")) {
                    button.disabled = true;
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
