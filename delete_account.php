<?php
session_start();

include 'db_koneksi.php';

$userId = $_POST['user_id'] ?? null;

if ($userId) {
    try {
        $stmt = $pdo->prepare("DELETE FROM userkitcat WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        // Hapus data dari sesi
        session_destroy();

        // Redirect ke halaman sukses atau halaman utama
        echo "<script>
                alert('Akun berhasil dihapus.');
                window.location.href = 'login.html';
            </script>";
            exit;
    } catch (PDOException $e) {
        echo "Kesalahan saat menghapus akun: " . $e->getMessage();
        exit;
    }
} else {
    echo "ID pengguna tidak ditemukan.";
    exit;
}
?>