<?php
session_start();

$host = "localhost";
$user = "anata_user";
$pass = "Medan2005"; 
$dbname = "anata_kitcat"; 

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

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