<?php
session_start();
$host = "aws-0-ap-southeast-1.pooler.supabase.com";
$port = '6543';
$dbname = "postgres";
$user = "postgres.xjkvjyrwkjcarvjeywvt";
$pass = "Medan2005";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            echo "<script>
                alert('Nama pengguna dan kata sandi harus diisi.');
                window.location.href = 'login.html';
            </script>";
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM userkitcat WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama_profil'] = $user['nama_profil'];
                $_SESSION['avatar'] = $user['avatar'];
                
                header("Location: beranda.html");
                exit;
            } else {
                echo "<script>
                    alert('Kata sandi salah.');
                    window.location.href = 'login.html';
                </script>";
            }
        } else {
            echo "<script>
                alert('Nama pengguna tidak ditemukan.');
                window.location.href = 'login.html';
            </script>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['get_user_id'])) {
        header('Content-Type: application/json');
        if (isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'success', 'user_id' => $_SESSION['user_id']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        }
        exit;
    }
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
