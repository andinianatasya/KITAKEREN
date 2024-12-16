<?php
session_start();
include 'db_koneksi.php';

$query = pg_query($conn,"SELECT id, avatar, username, maxpoint ,koin FROM userkitcat ORDER BY koin DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Peringkat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script>
        function hideLeaderboard() {
           
            window.location.href = 'ruangmain.html'; 
        }
    </script>
</head>
<body class="bg-oren text-white">
    <div class="absolute top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 backdrop-blur-sm">
        <img src="img/bgppnprngkat.png" alt=""
        class="w-full h-full object-cover filter blur-sm">
    </div>

    <div class="relative z-10 flex flex-col items-center w-full h-screen p-6">

        <h1 class="text-3xl font-bold mt-10 mb-6 text-white bg-oren rounded-full px-3 py-1">Papan Peringkat</h1>

        <div class="w-full text-xs md:text-2xl max-w-4xl bg-orenTua mt-3 rounded-t-lg shadow-lg flex text-center font-semibold">
            <div class="w-full p-4">Peringkat</div>
            <div class="w-full p-4">Total Skor</div>
            <div class="w-full p-4">Julukan</div>
        </div>

        <div id="leaderboard" class="w-full max-w-4xl bg-oren rounded-b-lg shadow-lg overflow-y-auto max-h-[400px]">
            <ul class="divide-y divide-black">
                <?php
                $counts = 1;
                while ($querys = pg_fetch_assoc($query)) {
                    $koin = $querys['koin'];
                    $max=$querys["maxpoint"];
                    if ($koin > $querys['maxpoint']) {
                       $max=$koin; 
                    }
                    $indeks_nilai = "-";
                    if ($counts == 1) {
                        echo "<li class='flex items-center text-center p-4 bg-yellow-400 text-oren font-semibold'>";
                    } elseif ($counts == 2) {
                        echo "<li class='flex items-center text-center p-4 bg-gray-300 text-oren font-semibold'>";
                    } elseif ($counts == 3) {
                        echo "<li class='flex items-center text-center p-4 bg-yellow-800 text-white font-semibold'>";
                    } else {
                        echo "<li class='flex items-center text-center p-4 bg-merahTua text-white font-semibold'>";
                    }
                    echo "<div class='w-1/6'>" . $counts . "</div>";

                    if ($querys['maxpoint'] > 10000) {
                        $indeks_nilai = "Sangat Mahir";
                    } elseif ($querys['maxpoint'] > 5000) {
                        $indeks_nilai = "Mahir";
                    } elseif ($querys['maxpoint'] > 1000) {
                        $indeks_nilai = "Menengah";
                    } elseif ($querys['maxpoint'] > 500) {
                        $indeks_nilai = "Pemula";
                    }

                    $avatar = $querys['avatar']; 
                    $avatar_path = "img/" . htmlspecialchars($avatar) . ".png";

                    echo "<div class='w-full md:w-2/6 flex items-center justify-start space-x-2 text-xs md:text-2xl'>";
                    echo "<img src='" . $avatar_path . "' alt='Profile' class='w-7 h-7 md:w-10 md:h-10 rounded-full'>";
                    echo "<span class='truncate'>" . $querys["username"] . "</span>";
                    echo "</div>";
                    echo "<div class='w-full md:w-1/6  text-xs md:text-2xl'>" . $max . "</div>";
                    echo "<div class='w-full md:w-1/6 text-xs md:text-2xl'>" . $indeks_nilai . "</div>";
                    echo "</li>";

                    $counts++;
                }
                ?>
            </ul>
        </div>
        <button id="tutupLeaderboard" class="mt-12 bg-oren text-white font-semibold py-2 px-4 rounded hover:bg-red-700" onclick="hideLeaderboard()">Tutup</button>
    </div>
</body>
</html>