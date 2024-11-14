<?php
require 'database_connection.php';

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $result = mysqli_query($conn, "SELECT nama_produk FROM stok_produk WHERE barcode = '$barcode'");
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode(['success' => true, 'nama_produk' => $data['nama_produk']]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>