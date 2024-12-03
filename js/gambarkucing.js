fetch('ambilkucing.php')
    .then(response => response.text())
    .then(imagePath => {
        console.log('Path gambar:', imagePath);
        document.getElementById('catImage').src = imagePath;
    })
.catch(error => console.error('Error:', error));