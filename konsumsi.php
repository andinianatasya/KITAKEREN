<?php
session_start();

$servername = "localhost";
$username = "anata_user";
$password = "Medan2005";
$dbname = "anata_kitcat";

$conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_produk = intval($_POST['id_produk']);

    $query = "SELECT jumlah_produk FROM penyimpanan WHERE id = $user_id AND id_produk = $id_produk";
    $result = pg_query($conn, $query);

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $jumlah_produk = $row['jumlah_produk'];

        if ($jumlah_produk > 1) {
            $updateQuery = "UPDATE penyimpanan SET jumlah_produk = jumlah_produk - 1 WHERE id = $user_id AND id_produk = $id_produk";
            pg_query($conn, $updateQuery);
            echo json_encode(["status" => "success", "message" => "Produk diperbarui"]);
        } else {
            $deleteQuery = "DELETE FROM penyimpanan WHERE id = $user_id AND id_produk = $id_produk";
            pg_query($conn, $deleteQuery);
            echo json_encode(["status" => "success", "message" => "Produk dihapus"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan"]);
    }

    exit;
}

$query = "SELECT id_produk FROM penyimpanan WHERE id = $user_id";
$result = pg_query($conn, $query);

$ids = [];
while ($row = pg_fetch_assoc($result)) {
    $ids[] = $row['id_produk'];
}

?>