function updateLapar(amount) {
    fetch('ambilStatusBar.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Jaringan buruk');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data yg diterima: ', data);
            let currentLapar = parseInt(data.lapar);
            if (isNaN(currentLapar)) {
                console.error('currentLapar tidak valid, tidak dapat dikonversi ke angka');
                return;
            }

            if (currentLapar < 100) {
                currentLapar += amount;
                if (currentLapar > 100) {
                    currentLapar = 100; 
                    alert("Bar lapar sudah penuh!");
                }

                updateStatusOnServer(currentLapar, data.sehat, data.energi, data.senang);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

function updateStatusOnServer(lapar, sehat, energi, senang) {
    console.log('Updating status on server:', { lapar, sehat, energi, senang });
    fetch('updateBar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lapar, sehat, energi, senang })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Jaringan buruk');
        }
        return response.json();
    })
    .then(() => {
        updateBarsDisplay(lapar, sehat, energi, senang);
    })
    .catch(error => {
        console.error('Terjadi masalah', error);
    });
}


document.querySelectorAll('[id^="makanButton"]').forEach(button => {
    button.addEventListener('click', function(event) {
        if (button.disabled) {
            event.preventDefault();
            event.stopImmediatePropagation();
            console.log(`${button.id} is disabled and cannot be clicked.`);
            return;
        }

        console.log(`${button.id} clicked`);
        updateLapar(20);
    });
});


document.querySelectorAll('[id^="minumButton"]').forEach(button => {
    button.addEventListener('click', function() {
        if (button.disabled) {
            event.preventDefault();
            event.stopImmediatePropagation();
            console.log(`${button.id} is disabled and cannot be clicked.`);
            return;
        }

        console.log(`${button.id} clicked`);
        updateLapar(5);
    });
});
