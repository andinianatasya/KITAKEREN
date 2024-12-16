<?php
session_start();

include 'db_koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Pengguna tidak terdaftar."]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

$lapar = isset($data['lapar']) ? (int)$data['lapar'] : 100;
$sehat = isset($data['sehat']) ? (int)$data['sehat'] : 100;
$energi = isset($data['energi']) ? (int)$data['energi'] : 100;
$senang = isset($data['senang']) ? (int)$data['senang'] : 100;

if ($lapar < 0 || $lapar > 100 || $sehat < 0 || $sehat > 100 || $energi < 0 || $energi > 100 || $senang < 0 || $senang > 100) {
    echo json_encode(["status" => "error", "message" => "Nilai harus antara 0 dan 100."]);
    exit;
}

$sql = "INSERT INTO kucing (id, lapar, sehat, energi, senang) 
        VALUES (?, ?, ?, ?, ?)
        ON CONFLICT (id) 
        DO UPDATE SET 
            lapar = EXCLUDED.lapar,
            sehat = EXCLUDED.sehat,
            energi = EXCLUDED.energi,
            senang = EXCLUDED.senang";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $user_id);
$stmt->bindParam(2, $lapar);
$stmt->bindParam(3, $sehat);
$stmt->bindParam(4, $energi);
$stmt->bindParam(5, $senang);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => $stmt->errorInfo()]);
} else {
    echo json_encode(["status" => "success", "message" => "Status berhasil diperbarui."]);
}

$pdo = null;
?>