<?php
session_start();
$host = "localhost";
$dbname = "Kitcat";
$user = "postgres";
$password = "Medan2005"; 

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

$user_id = $_SESSION['user_id']; 

$sql = "SELECT level FROM userkitcat WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $level = $row['level'];
    
    if ($level >= 1 && $level <= 30) {
        $umur = 'bayi';
    } elseif ($level >= 31 && $level <= 71) {
        $umur = 'anak';
    } else {
        $umur = 'dewasa';
    }
} else {
    die("Level user tidak ditemukan.");
}

$sql = "SELECT * FROM kucing WHERE id = :user_id"; 
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $kucing = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die("Data kucing tidak ditemukan.");
}

$path_gambar = '';
$kondisi = '';

if ($umur == 'bayi') {
    if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
        $kondisi = 'ngantuk';
        $path_gambar = 'img/ngantuk_bayi.png';
    } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
        $kondisi = 'penasaran';
        $path_gambar = 'img/penasaran_bayi.png';
    } else {
        $path_gambar = 'img/default_bayi.png'; 
    }
} elseif ($umur == 'anak') {
    if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
        $kondisi = 'ngantuk';
        $path_gambar = 'img/ngantuk_anak.png';
    } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
        $kondisi = 'penasaran';
        $path_gambar = 'img/penasaran_anak.png';
    } else {
        $path_gambar = 'img/default_anak.png'; 
    }
} else { 
    if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
        $kondisi = 'ngantuk';
        $path_gambar = 'img/ngantuk_dewasa.png';
    } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
        $kondisi = 'penasaran';
        $path_gambar = 'img/penasaran_dewasa.png';
    } else {
        $path_gambar = 'img/default_dewasa.png'; 
    }
}

if (isset($kucing['motif'])) {
    if ($kucing['motif'] == 'motif1') {

        if ($umur == 'bayi') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif1/ngantuk_bayi.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif1/penasaran_bayi.png';
            } else {
                $path_gambar = 'img/motif1/default_bayi.png'; 
            }
        } elseif ($umur == 'anak') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif1/ngantuk_anak.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif1/penasaran_anak.png';
            } else {
                $path_gambar = 'img/motif1/default_anak.png'; 
            }
        } else { 
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif1/ngantuk_dewasa.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif1/penasaran_dewasa.png';
            } else {
                $path_gambar = 'img/motif1 /default_dewasa.png'; 
            }
        }
    } elseif ($kucing['motif'] == 'motif2') {

        if ($umur == 'bayi') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif2/ngantuk_bayi.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif2/penasaran_bayi.png';
            } else {
                $path_gambar = 'img/motif2/default_bayi.png'; 
            }
        } elseif ($umur == 'anak') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif2/ngantuk_anak.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif2/penasaran_anak.png';
            } else {
                $path_gambar = 'img/motif2/default_anak.png'; 
            }
        } else { 
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif2/ngantuk_dewasa.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif2/penasaran_dewasa.png';
            } else {
                $path_gambar = 'img/motif2/default_dewasa.png'; 
            }
        }
    } elseif ($kucing['motif'] == 'motif3') {

        if ($umur == 'bayi') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif3/ngantuk_bayi.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif3/penasaran_bayi.png';
            } else {
                $path_gambar = 'img/motif3/default_bayi.png'; 
            }
        } elseif ($umur == 'anak') {
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif3/ngantuk_anak.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif3/penasaran_anak.png';
            } else {
                $path_gambar = 'img/motif3/default_anak.png'; 
            }
        } else { 
            if ($kucing['lapar'] < 10 && $kucing['energi'] < 10) {
                $kondisi = 'ngantuk';
                $path_gambar = 'img/motif3/ngantuk_dewasa.png';
            } elseif ($kucing['senang'] < 10 && $kucing['sehat'] < 10) {
                $kondisi = 'penasaran';
                $path_gambar = 'img/motif3/penasaran_dewasa.png';
            } else {
                $path_gambar = 'img/motif3/default_dewasa.png'; 
            }
        }
    } else {

        if ($umur == 'bayi') {
            $path_gambar = 'img/default_bayi.png';
        } elseif ($umur == 'anak') {
            $path_gambar = 'img/default_anak.png';
        } else {
            $path_gambar = 'img/default_dewasa.png';
        }
    }
}

$sql = "UPDATE kucing SET kondisi = :kondisi, umur = :umur, path_gambar = :path_gambar WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':kondisi', $kondisi, PDO::PARAM_STR);
$stmt->bindParam(':umur', $umur, PDO::PARAM_STR);
$stmt->bindParam(':path_gambar', $path_gambar, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Data umur dan path_gambar berhasil disimpan.";
} else {
    echo "Gagal menyimpan data umur dan path_gambar.";
}

echo "<h2>kondisi Kucing: " . htmlspecialchars($kondisi) . "</h2>";
echo "<img src='" . htmlspecialchars($path_gambar) . "' alt='Gambar Kucing'>";
?>