<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$dbname = "anata_kitcat";
$user = "anata_user";
$password = "Medan2005";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $e->getMessage()]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID tidak ditemukan."]);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"));

if (isset($data->motif) && isset($data->harga)) {
    $motif = trim($data->motif);
    $harga = $data->harga;

    if (!is_numeric($harga) || $harga <= 0) {
        echo json_encode(["status" => "error", "message" => "Harga tidak valid."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT koin FROM userkitcat WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['koin'] >= $harga) {
            $newKoin = $result['koin'] - $harga;

            $stmt = $pdo->prepare("UPDATE userkitcat SET koin = :new_koin WHERE id = :user_id");
            $stmt->bindParam(':new_koin', $newKoin, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $pdo->prepare("UPDATE kucing SET motif = :motif WHERE id = :user_id");
            $stmt->bindParam(':motif', $motif, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Pembelian motif berhasil!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Terjadi kesalahan saat menyimpan motif."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Koin tidak cukup."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Motif atau harga tidak diberikan."]);
}
