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
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("SELECT * FROM userkitcat WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo "<script>
                alert('Nama pengguna sudah terdaftar. Silahkan pilih nama pengguna lain.');
                window.location.href = 'login.html';
            </script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO userkitcat (username, password) VALUES (:username, :password) RETURNING id");
            $stmt->execute([
                'username' => $username,
                'password' => $password
            ]);
            $userId = $stmt->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO kucing (id, umur, kondisi, path_gambar) 
                                    VALUES (:id, :umur, :kondisi, :path_gambar)");
            $stmt->execute([
                'id' => $userId,
                'umur' => 'bayi',           
                'kondisi' => 'default',      
                'path_gambar' => 'img/default_bayi.png' 
            ]);

            echo "<script>
                alert('Pendaftaran berhasil! Silakan Masuk.');
                window.location.href = 'login.html';
            </script>";
            exit;
        }
    }
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
