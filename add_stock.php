<?php
require 'function.php'; // Pastikan function.php memiliki koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];
    $qty = 1; // Misalnya, setiap scan menambah 1 stok; sesuaikan dengan kebutuhan

    // Cek apakah produk dengan barcode tersebut ada di database
    $query = mysqli_query($conn, "SELECT * FROM stok_produk WHERE barcode='$barcode'");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $idproduk = $data['idproduk'];
        
        // Update stok produk dengan menambah quantity
        $updateQuery = mysqli_query($conn, "UPDATE stok_produk SET quantity = quantity + $qty WHERE idproduk='$idproduk'");
        
        if ($updateQuery) {
            echo "Stok produk berhasil ditambah.";
        } else {
            echo "Gagal menambah stok produk.";
        }
    } else {
        echo "Produk tidak ditemukan.";
    }
} else {
    echo "Invalid request.";
}
?>
