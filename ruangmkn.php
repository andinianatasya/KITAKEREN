<?php
session_start();

include 'db_koneksi.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_produk = intval($_POST['id_produk']);

    $query = "SELECT jumlah_produk FROM penyimpanan WHERE id = $user_id AND id_produk = $id_produk";
    $result = pg_query($conn, $query);

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $jumlah_produk = $row['jumlah_produk'];

        if ($jumlah_produk > 1) {
            $updateQuery = "UPDATE penyimpanan SET jumlah_produk = jumlah_produk - 1 WHERE id = $user_id AND id_produk = $id_produk";
            pg_query($conn, $updateQuery);
            echo json_encode(["status" => "success", "message" => "Produk diperbarui"]);
        } else {
            $deleteQuery = "DELETE FROM penyimpanan WHERE id = $user_id AND id_produk = $id_produk";
            pg_query($conn, $deleteQuery);
            echo json_encode(["status" => "success", "message" => "Produk dihapus"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan"]);
    }

    exit;
}

$query = "SELECT id_produk FROM penyimpanan WHERE id = $user_id";
$result = pg_query($conn, $query);

$ids = [];
while ($row = pg_fetch_assoc($result)) {
    $ids[] = $row['id_produk'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/logo(1).png" type="image/png">
    <style>
        .ga-aktif{
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
    <title>Ruang Makan</title>
</head>

<body class="overflow-hidden">
    <div class="bg-bgDapur bg-cover bg-center absolute inset-0 flex justify-center items-center">
        <img id="catImage" src="<?php echo htmlspecialchars($path_gambar); ?>" alt="Kucing" class="h-80 mt-20 cursor-pointer">
        <audio id="meong" src="img/meow.mp3"></audio>
    </div>

    <nav class="fixed top-0 left-0 right-0 bg-oren p-2">
        <ul class="flex justify-around items-center">
            <li class="text-center hover:opacity-50 duration-300">
                <a href="profil.php?ruangan=ruangmkn.php" class="flex items-center">
                    <img src="img/profil.svg" alt="Profil Icon" class="flex flex-col items-center w-10 h-10 mb-1">
                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Profil</span>
                </a>
                
            </li>

            <li class="text-center">
                <button id="dropdownButton" class="flex items-center hover:opacity-50 duration-300">
                    <img src="img/iconruangan.png" alt="ruangan" class="w-10 h-10 mb-1">
                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruangan</span>
                </button>
            
                <div>
                    <ul id="lokasidropdown" class="bg-oren rounded-md mt-4 absolute rounded-me md:w-40 w-20 p-2 0focus:outline-orenTua hidden">
                        <li>
                            <button class="hover:opacity-50 duration-300">
                                <a href="beranda.html" class="flex items-center" class="">
                                    <img src="img/iconruangtamu.png" alt="Ruang Tamu" class="w-16 mb-1">
                                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruang Tamu</span>
                                </a>
                            </button>
                        </li>
                        <li>
                            <button class="hover:opacity-50 duration-300">
                                <a href="ruangmkn.php" class="flex items-center">
                                    <img src="img/iconmakan.png" alt="Ruang Makan" class="w-16 mb-1">
                                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruang Makan</span>
                                </a>
                            </button>
                        </li>
                        <li>
                            <button class="hover:opacity-50 duration-300">
                                <a href="ruangtdr.html" class="flex items-center">
                                    <img class="w-16 mb-1" src="img/icontidur.png" alt="Ruang Tidur">
                                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruang Tidur</span>
                                </a>
                            </button>                 
                        </li>
                        <li>
                            <button class="hover:opacity-50 duration-300">
                                <a href="ruangmain.html" class="flex items-center">
                                    <img class="w-16 mb-1" src="img/iconmain.png" alt="Ruang Main">
                                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruang Main</span>
                                </a>
                            </button>                                
                        </li>
                        <li>
                            <button class="hover:opacity-50 duration-300">
                                <a href="ruangmandi.html" class="flex items-center">
                                    <img class="w-16 mb-1" src="img/iconmandi.png" alt="">
                                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold">Ruang Mandi</span>
                                </a>
                            </button>                               
                        </li>
                    </ul>
                </div>
            </li>

            <li class="text-center hover:opacity-50 duration-300">
                <a href="#" class="flex items-center">
                    <img id="bukaChat" src="img/chat.svg" alt="Chat Icon" class="w-10 mb-1">
                    <span class="hidden md:block text-white font-sans pl-4 pb-2 font-bold" >Obrolan Global</span>
                </a>
                
            </li>
        </ul>
    </nav>

    <div class="fixed bottom-0 left-0 right-0 flex justify-around py-4 px-5 scroll-pl-6 snap-x overflow-x-auto">
        <div class="flex space-x-5">

            <button id="4" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan1')">
                <img id="makanButton1" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/ikan.svg" alt="makanan">
            </button>

            <button id="2" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan2')">
                <img id="obatButton1" class="obat rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/obat1.png" alt="obat">
            </button> 

            <button id="3" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan3')">
                <img id="makanButton2" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/sayur.svg" alt="makanan">
            </button>

            <button id="1" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan4')">
                <img id="makanButton3" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/makanan1.png" alt="makanan">
            </button>

            <button id="5" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan6')">
                <img id="makanButton5" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/makanan3.png" alt="makanan">
            </button> 

            <button id="7" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan7')">
                <img id="minumButton1" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/minum.svg" alt="minuman">
            </button>  

            <button id="8" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan8')">
                <img id="minumButton2" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/minuman1.png" alt="minuman">
            </button> 

            <button id="9" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan9')">
                <img id="minumButton3" class="rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/minuman2.png" alt="minuman">
            </button> 

            <button id="10" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan10')">
                <img id="obatButton2" class="obat rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/obat2.png" alt="obat">
            </button>   
          
            <button id="11" class="w-16 h-16 shadow-cartoon rounded-full snap-start ga-aktif makan" disabled onclick="updateExp('makanan11')">
                <img id="obatButton3" class="obat rounded-full active:border-2 active:border-white active:translate-y-[5px] active:duration-300" src="img/makanminum/obat3.png" alt="obat">
            </button>   
        </div>
    </div>

    <script>
    const enabledIds = <?php echo json_encode($ids); ?>;

    enabledIds.forEach(id => {
        const button = document.getElementById(id);
        if (button) {
            button.disabled = false;
            button.classList.remove('ga-aktif');
            button.addEventListener('click', () => updateProduk(id));
        }
    });

    function updateProduk(id_produk) {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_produk=${id_produk}`,
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan!');
        });
    }
    </script>

    <script src="js/index.js"></script>
    <script src="js/chatglobal.js"></script>
    <script src="js/interaksiKucing.js"></script>
    <script src="js/updateBarLapar.js"></script>
    <script src="js/updateBarSehat.js"></script>
    <script src="js/bar.js"></script>
    <script src="js/exp.js"></script>
    <script src="js/gambarkucing.js"></script>
</body>
</html>