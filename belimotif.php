<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$dbname = "Kitcat";
$user = "postgres";
$password = "Medan2005"; 

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "terjadi kesalahan", "message" => "Koneksi gagal: " . $e->getMessage()]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "terjadi kesalahan", "message" => "User  ID tidak ditemukan."]);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["status" => "gagal", "message" => "Data JSON tidak valid."]);
    exit;
}

if (isset($data->motif) && isset($data->harga)) {
    $motif = trim($data->motif);
    $harga = $data->harga;

    // Validasi harga
    if (!is_numeric($harga) || $harga <= 0) {
        echo json_encode(["status" => "gagal", "message" => "Harga tidak valid."]);
        exit;
    }

    // Periksa apakah user memiliki cukup koin
    $stmt = $pdo->prepare("SELECT koin FROM userkitcat WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['koin'] >= $harga) {
        // Kurangi koin dari tabel userkitcat
        $newKoin = $result['koin'] - $harga;
        $stmt = $pdo->prepare("UPDATE userkitcat SET koin = :new_koin WHERE id = :user_id");
        $stmt->bindParam(':new_koin', $newKoin, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Masukkan motif ke tabel kucing
        $stmt = $pdo->prepare("INSERT INTO kucing (motif) VALUES (:motif)");
        $stmt->bindParam(':motif', $motif, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(["status" => "sukses", "message" => "Pembelian berhasil!"]);
    } else {
        echo json_encode(["status" => "gagal", "message" => "Koin tidak cukup."]);
    }
} else {
    echo json_encode(["status" => "terjadi kesalahan", "message" => "Motif atau harga tidak diberikan."]);
}
?>