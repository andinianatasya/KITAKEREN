<?php
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$port = '6543';
$dbname = 'postgres';
$user = 'postgres.xjkvjyrwkjcarvjeywvt';
$password = 'Medan2005';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Koneksi berhasil ke Transaction Pooler!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
