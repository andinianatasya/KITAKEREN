<?php
session_start();

include 'db_koneksi.php';

if (isset($_SESSION['user_id'])) {
    $sql = "SELECT nama_profil, avatar FROM userkitcat WHERE id = :user_id";
    $sql = "SELECT nama_profil, avatar FROM userkitcat WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['nama_profil'] = $user['nama_profil'];
        $_SESSION['avatar'] = $user['avatar'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru = htmlspecialchars(trim($_POST['nama']));
    $avatar = isset($_POST['avatar']) ? htmlspecialchars(trim($_POST['avatar'])) : null;

    $max_length = 15;
    $error = false;
    
    if (strlen($nama_baru) > $max_length) {
        $_SESSION['message'] = "Error: Nama pengguna tidak boleh lebih dari $max_length karakter.";
        $error = true;
    } else if (!empty($nama_baru) && isset($_SESSION['user_id'])) {
        $sql = "UPDATE userkitcat SET nama_profil = :nama_profil WHERE id = :user_id"; 
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':nama_profil', $nama_baru);
        $stmt->bindParam(':user_id', $_SESSION['user_id']); 

        if ($stmt->execute()) {
            $_SESSION['nama_profil'] = $nama_baru;
            $_SESSION['message'] = "Profil berhasil diperbarui.";
        } else {
            $_SESSION['message'] = "Error: Gagal memperbarui profil.";
        }
    }

    if (!$error && $avatar && isset($_SESSION['user_id'])) {
        $sql = "UPDATE userkitcat SET avatar = :avatar WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        if ($stmt->execute()) {
            $_SESSION['avatar'] = $avatar; 
            $_SESSION['message'] = "Profil berhasil diperbarui.";
        } else {
            $_SESSION[' message'] = "Error: Gagal memperbarui profil.";
        }
    }

    header("Location: profil.php");
    exit();
}

$conn = null;
?>