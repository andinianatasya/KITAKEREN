function updateEnergi(amount) {
    fetch('ambilStatusBar.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Jaringan buruk');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data yang diterima: ', data);

            let currentEnergi = parseInt(data.energi);
            if (isNaN(currentEnergi)) {
                console.error('currentEnergi tidak valid, tidak dapat dikonversi ke angka');
                return;
            }

            if (currentEnergi <= 60) {
                currentEnergi += amount;
                if (currentEnergi > 100) {
                    currentEnergi = 100;
                    alert("Bar energi sudah penuh!");
                }
                updateStatusOnServer(data.lapar, data.sehat, currentEnergi, data.senang);
            }

            toggleEnergiButtons(currentEnergi);
        })
        .catch(error => {
            console.error('Error saat mengambil data:', error);
        });
}

function toggleEnergiButtons(currentEnergi) {
    const energiButton = document.getElementById('lampu');
    const message = document.getElementById('message');
    const overlay = document.getElementById('matiLampu');
    const closeButton = document.getElementById('closeButton');
    const catImage = document.getElementById('catImage');

    if (currentEnergi >= 50) {
        energiButton.classList.add('opacity-50', 'cursor-not-allowed');
        energiButton.setAttribute('aria-disabled', 'true');
        energiButton.disabled = true;

        message.classList.remove('hidden');
        message.classList.add('block');

        overlay.classList.add('hidden');
        setTimeout(() => {
            message.classList.add('hidden');
            message.classList.remove('block');
            window.location.href = 'ruangtdr.html';
        }, 2000);
    } else {
        energiButton.classList.remove('opacity-50', 'cursor-not-allowed');
        energiButton.removeAttribute('aria-disabled');
        energiButton.disabled = false;

        message.classList.add('hidden');
        overlay.classList.remove('hidden');

        closeButton.classList.remove('hidden');
        fetchCatData(catImage);

        closeButton.addEventListener('click', function() {
            overlay.classList.add('hidden');
            closeButton.classList.add('hidden');
            window.location.href = 'ruangtdr.html';
        });
    }
}

function updateStatusOnServer(lapar, sehat, energi, senang) {
    console.log('Mengupdate status bar:', { lapar, sehat, energi, senang });
    fetch('updateBar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ lapar, sehat, energi, senang })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Jaringan buruk saat memperbarui status');
        }
        return response.json();
    })
    .then(() => {
        updateBarsDisplay(lapar, sehat, energi, senang);
    })
    .catch(error => {
        console.error('Terjadi masalah saat memperbarui status:', error);
    });
}

function fetchCatData(catImage) {
    fetch('kucingtidur.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data kucing');
            }
            return response.json();
        })
        .then(data => {
            if (data.length > 0) {
                const { umur, motif } = data[0];
                updateCatImage(catImage, umur, motif);
            } else {
                console.log('Tidak ada data kucing.');
            }
        })
        .catch(error => {
            console.error('Error saat mengambil data kucing:', error);
        });
}

function updateCatImage(catImage, umur, motif) {
    let path_gambar = 'img/';
    if (!motif || motif === 'null') {
        path_gambar += `tidur_${umur}.png`;
    } else {
        path_gambar += `${motif}/tidur_${umur}${motif.slice(-1)}.png`;
    }
    catImage.src = path_gambar;
    console.log('Gambar kucing diperbarui ke:', path_gambar);
}

document.getElementById('lampu').addEventListener('click', function() {
    const energiButton = this;
    if (energiButton.disabled) {
        console.log('Tombol dinonaktifkan, tidak dapat menambah energi.');
        return;
    }

    console.log('Tombol energi diklik');
    updateEnergi(20);
});
