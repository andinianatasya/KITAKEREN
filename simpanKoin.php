<?php
session_start();
header('Content-Type: application/json');

include 'db_koneksi.php';

function saveCoins($userId, $koin) {
    global $pdo;

    $stmt = $pdo->prepare("
    UPDATE userkitcat 
    SET koin = koin + :koin, 
        maxpoint = GREATEST(maxpoint, koin + :koin)
    WHERE id = :user_id
    ");

    $stmt->bindParam(':koin', $koin, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "sukses", "message" => "Koin berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "terjadi kesalahan", "message" => "Gagal menyimpan koin."]);
    }
}

function getCoins($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT koin FROM userkitcat WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode(["status" => "sukses", "koin" => $result['koin']]);
    } else {
        echo json_encode(["status" => "terjadi kesalahan", "message" => "User  tidak ditemukan."]);
    }
}

$data = json_decode(file_get_contents("php://input"));

if (isset($data->koin)) { 
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $koin = $data->koin;

        saveCoins($userId, $koin);
    } else {
        echo json_encode(["status" => "terjadi kesalahan", "message" => "User  ID tidak ditemukan."]);
    }
} else if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    getCoins($userId);
} else {
    echo json_encode(["status" => "terjadi kesalahan", "message" => "User  ID tidak ditemukan."]);
}
?>