<?php
session_start();

include 'db_koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID tidak ditemukan."]);
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT umur, motif FROM kucing WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bindValue(1, $userId, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC); 
echo json_encode($data); 


$stmt = null; 
$conn = null;
?>