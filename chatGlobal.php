<?php
session_start();
$host = "localhost";
$dbname = "anata_kitcat";
$user = "anata_user";
$password = "Medan2005"; 
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}
$user_id = $_SESSION['user_id']; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesan = $_POST['pesan'];
    $query_insert = "INSERT INTO obrolanglobal (id, pesan, dikirim) VALUES (:id, :pesan, NOW())";
    $stmt_insert = $pdo->prepare($query_insert);
    $stmt_insert->execute(['id' => $user_id, 'pesan' => $pesan]);
    $query_user = "SELECT avatar, nama_profil FROM userkitcat WHERE id = :user_id";
    $stmt_user = $pdo->prepare($query_user);
    $stmt_user->execute(['user_id' => $user_id]);
    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
    if ($user_data) {
        $avatar = htmlspecialchars($user_data['avatar']); 
        $avatar_path = "img/" . $avatar . ".png"; 
    
        if (!file_exists($avatar_path)) {
            $avatar_path = "img/avatar3.png"; 
        }
        
        echo json_encode([
            'avatar' => $avatar_path,
            'nama_profil' => htmlspecialchars($user_data['nama_profil']),
            'pesan' => htmlspecialchars($pesan) 
        ]);
    } else {
        echo json_encode([
            'error' => 'User  not found'
        ]);
    }
    exit;
}
$query_chat = "SELECT c.pesan, c.dikirim, k.avatar, k.nama_profil FROM obrolanglobal c JOIN userkitcat k ON c.id = k.id ORDER BY c.dikirim ASC";
$stmt_chat = $pdo->prepare($query_chat);
$stmt_chat->execute();
$result_chat = $stmt_chat->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo(1).png" type="image/png">
    <title>Obrolan Global</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="overflow-hidden">
    <div class="h-screen bg-bgGlobalchat bg-cover bg-center flex items-center justify-center">
        <div id="chatGlobal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 backdrop-blur-sm">
            <div class="bg-oren rounded-lg shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-4 relative">
                <button id="tutupChat" class="absolute top-2 right-2 text-orange-200 hover:text-orange-400 text-4xl">&times;</button>
                <h2 class="text-xl font-semibold text-center mb-4 text-orange-200">Obrolan Global</h2>
                <div id="chatContent" class="max-h-60 overflow-y-auto mb-4">
                    <?php foreach ($result_chat as $chat_data): ?>
                    <?php
                    $avatar = htmlspecialchars($chat_data['avatar']);
                    $avatar_path = "img/" . $avatar . ".png";
                    if (!file_exists($avatar_path)) {
                        $avatar_path = "img/avatar3.png";
                    }
                    ?>
                        <div class="flex items-start mb-2">
                            <img src="<?php echo $avatar_path; ?>" class="w-10 h-10 rounded-full mr-2">
                            <div class="bg-orange-200 rounded-lg p-2 text-merahTua">
                                <p class="font-bold"><?php echo htmlspecialchars($chat_data['nama_profil']); ?></p>
                                <p><?php echo htmlspecialchars($chat_data['pesan']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Input Pesan -->
                <div class="flex items-center">
                    <textarea id="chatInput" class="border rounded w-full p-2 mr-2" placeholder="Tulis pesan..."></textarea>
                    <button id="sendMessage" class ="bg-merahTua text-orange-200 rounded px-4">Kirim</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#sendMessage').click(function() {
                var pesan = $('#chatInput').val();
                if (pesan.trim() === '') {
                    alert('Pesan tidak boleh kosong!');
                    return;
                }
                $.ajax({
            type: 'POST',
            url: '',
            data: { pesan: pesan },
            success: function(response) {
                var data = JSON.parse(response);
                
                $('#chatContent').append(`
                    <div class="flex items-start mb-2">
                        <img src="${data.avatar}" alt="${data.nama_profil}" class="w-10 h-10 rounded-full mr-2">
                        <div class="bg-orange-200 rounded-lg p-2 text-merahTua">
                            <p class="font-bold">${data.nama_profil}</p>
                            <p>${data.pesan}</p>
                        </div>
                    </div>
                `);
                $('#chatInput').val('');
                $('#chatContent').scrollTop($('#chatContent')[0].scrollHeight);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengirim pesan.');
            }
        });
    });

    function fetchNewMessages() {
        $.ajax({
            type: 'GET',
            url: '',
            dataType: 'json',
            success: function(data) {
                $('#chatContent').empty(); 
                $.each(data, function(index, chat_data) {
                    $('#chatContent').append(`
                        <div class="flex items-start mb-2">
                            <img src="${chat_data.avatar}" alt="${chat_data.nama_profil}" class="w-10 h-10 rounded-full mr-2">
                            <div class="bg-orange-200 rounded-lg p-2 text-merahTua">
                                <p class="font-bold">${chat_data.nama_profil}</p>
                                <p>${chat_data.pesan}</p>
                            </div>
                        </div>
                    `);
                });
                $('#chatContent').scrollTop($('#chatContent')[0].scrollHeight);
            },
            error: function() {
                console.error('Terjadi kesalahan saat mengambil pesan baru.');
            }
        });
    }
            setInterval(fetchNewMessages, 1000);
        });
        document.getElementById("tutupChat").addEventListener("click", tutupChat);
    function tutupChat() {
        const previousPage = sessionStorage.getItem("previousPage");
        if (previousPage) {
            window.location.href = previousPage;
        } else {
            window.location.href = "beranda.html";
        }
    }
    </script>
</body>
</html>
