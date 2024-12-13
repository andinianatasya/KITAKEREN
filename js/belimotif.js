document.addEventListener('DOMContentLoaded', () => {
    const beliMotif = document.querySelectorAll('.belimotif');

    beliMotif.forEach(button => {
        button.addEventListener('click', function() {
            const motif = this.getAttribute("data-motif");
            const harga = parseInt(this.getAttribute("data-harga"));

            if (isNaN(harga) || harga <= 0) {
                alert("Harga tidak valid. Silakan periksa data item.");
                return;
            }

            console.log("Button clicked, motif: " + motif + ", harga: " + harga);

            fetch('belimotif1.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ motif: motif, harga: harga })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                alert(data.message);
                if (data.status === 'sukses') {
                    console.log('Pembelian berhasil, memuat ulang halaman...');
                    tampilkanKoin();
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});