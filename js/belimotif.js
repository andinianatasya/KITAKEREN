document.addEventListener('DOMContentLoaded', () => {
    const beliButtons = document.querySelectorAll('.belimotif');

    beliButtons.forEach(button => {
        button.addEventListener('click', function() {
            const motif = this.getAttribute("data-motif"); // Ambil motif dari atribut data
            const harga = parseInt(this.getAttribute("data-harga")); // Ambil harga dari atribut data

            // Pastikan harga valid
            if (isNaN(harga) || harga <= 0) {
                alert("Harga tidak valid. Silakan periksa data item.");
                return;
            }

            console.log("Button clicked, motif: " + motif + ", harga: " + harga);

            // Kirim data ke PHP
            fetch('belimotif.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ motif: motif, harga: harga })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'sukses') {
                    // Update tampilan koin atau gambar kucing jika perlu
                    tampilkanKoin();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});