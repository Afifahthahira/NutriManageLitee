<?php
session_start();

// Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "db_nutrimanagenutri");

//menambah produk baru
if (isset($_POST['addnewproduk'])) {
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    //soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //mengambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); // mengambil ekstensi
    $ukuran = $_FILES['file']['size']; // mengambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //mengambil lokasi file

    //penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //mengabungkan nama file yang diengkripsi dengan ekstensinya

    //penamaan file -> engkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggabungkan nama file yg diengkripsi dengan ekstensinya

    //validasi udah ada atau belum
    $cek = mysqli_query($conn, "SELECT * FROM stok_produk WHERE nama_produk='$namaproduk'");
    $hitung = mysqli_num_rows($cek);

    if ($hitung < 1) {
        //jika belum ada

        //proses upload gambar
        if (in_array($ekstensi, $allowed_extension) === true) {
            //validasi ukuran filenya
            if ($ukuran < 15000000) {
                move_uploaded_file($file_tmp, 'image/' . $image);

                $addtotable = mysqli_query($conn, "INSERT INTO stok_produk (image, nama_produk, deskripsi, stok) values ('$image', '$namaproduk', '$deskripsi', '$stok')");
                if ($addtotable) {
                    header('location:stok.php');
                } else {
                    echo 'Gagal';
                    header('location:stok.php');
                }
            } else {
                // kalau filenya lebih dari 1.5mb
                echo '
                <script>
                alert("Ukuran terlau besar");
                window.location.href="stok.php";
                </script>
                ';
            }

        } else {
            // kalau file gambar tidak png/ jpg
            echo '
            <script>
            alert("File harus png/jpg");
            window.location.href="stok.php";
            </script>
            ';
        }

    } else {
        // jika sudah ada
        echo '
        <script>
        alert("Nama produk sudah terdaftar");
        window.location.href="stok.php";
        </script>
        ';
    }
}
;

// menambah produk masuk
if (isset($_POST['produkmasuk'])) {
    $produknya = $_POST['produknya'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$produknya'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];
    $tambahakanstoksekarangdenganquantity = $stoksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO produk_masuk (idproduk, keterangan, qty) values ('$produknya', '$keterangan', '$qty')");
    $updatestokmasuk = mysqli_query($conn, "UPDATE stok_produk SET stok='$tambahakanstoksekarangdenganquantity' WHERE idproduk='$produknya'");
    if ($addtomasuk && $updatestokmasuk) {
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}


// menambah produk keluar
if (isset($_POST['addprodukkeluar'])) {
    $produknya = $_POST['produknya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$produknya'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];

    if ($stoksekarang >= $qty) {
        // kalau produknya ckup
        $tambahakanstoksekarangdenganquantity = $stoksekarang - $qty;

        $addtokeluar = mysqli_query($conn, "INSERT INTO produk_keluar (idproduk, penerima, qty) values ('$produknya', '$penerima', '$qty')");
        $updatestokkeluar = mysqli_query($conn, "UPDATE stok_produk SET stok='$tambahakanstoksekarangdenganquantity' WHERE idproduk='$produknya'");
        if ($addtokeluar && $updatestokkeluar) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        // kalau produknya tidak cukup
        echo '
    <script>
    alert("Stok saat ini tidak mencukupi");
    window.location.href="keluar.php";
    </script>';
    }
}

//update info barang
if (isset($_POST['updateproduk'])) {
    $idproduk = $_POST['idproduk'];
    $namaproduk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];

    //soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //mengambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); // mengambil ekstensi
    $ukuran = $_FILES['file']['size']; // mengambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //mengambil lokasi file

    //penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //mengabungkan nama file yang diengkripsi dengan ekstensinya


    if ($ukuran == 0) {
        //jika tidak ingin diupload
        $update = mysqli_query($conn, "UPDATE stok_produk SET nama_produk= '$namaproduk', deskripsi='$deskripsi' WHERE idproduk ='$idproduk'");
        if ($update) {
            header('location:stok.php');
        } else {
            echo 'Gagal';
            header('location:stok.php');
        }
    } else {
        // jika ingin diupload
        move_uploaded_file($file_tmp, 'image/' . $image);
        $update = mysqli_query($conn, "UPDATE stok_produk SET nama_produk= '$namaproduk', deskripsi='$deskripsi', image='$image' WHERE idproduk ='$idproduk'");
        if ($update) {
            header('location:stok.php');
        } else {
            echo 'Gagal';
            header('location:stok.php');
        }

    }
}

// menghapus produk dari stok
if (isset($_POST['deleteproduk'])) {
    $idproduk = $_POST['idproduk']; //idproduk

    $gambar = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='idproduk'");
    $get = mysqli_fetch_array($gambar);
    $image = 'image/' . $get['image'];
    unlink($image);

    $hapus = mysqli_query($conn, "DELETE FROM stok_produk WHERE idproduk='$idproduk'");
    if ($hapus) {
        header('location:stok.php');
    } else {
        echo 'Gagal';
        header('location:stok.php');
    }
}

//mengubah data produk masuk
if (isset($_POST['updateprodukmasuk'])) {
    $idproduk = $_POST['idproduk'];
    $idm = $_POST['idmasuk'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$idproduk'");
    $stoknya = mysqli_fetch_array($lihatstok);
    $stokskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM produk_masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stokskrg - $selisih;
        $kurangistoknya = mysqli_query($conn, "UPDATE stok_produk SET stok='$kurangin' WHERE idproduk='$idproduk'");
        $updatenya = mysqli_query($conn, "UPDATE produk_masuk set qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
        if ($kurangistoknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stokskrg + $selisih;
        $kurangistoknya = mysqli_query($conn, "UPDATE stok_produk SET stok='$kurangin' WHERE idproduk='$idproduk'");
        $updatenya = mysqli_query($conn, "UPDATE produk_masuk set qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
        if ($kurangistoknya && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } 

    }

// menghapus produk masuk
if (isset($_POST['deleteprodukmasuk'])) {
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];
    $idm = $_POST['idmasuk'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$idproduk'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "UPDATE stok_produk set stok='$selisih' WHERE idproduk='$idproduk'");
    $hapusdata = mysqli_query($conn, "DELETE FROM produk_masuk WHERE idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}


//mengubah data produk keluar
if (isset($_POST['updateprodukkeluar'])) {
    $idproduk = $_POST['idproduk'];
    $idk = $_POST['idkeluar'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$idproduk'");
    $stoknya = mysqli_fetch_array($lihatstok);
    $stokskrg = $stoknya['stok'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM produk_keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stokskrg - $selisih;
        $kurangistoknya = mysqli_query($conn, "UPDATE stok_produk SET stok='$kurangin' WHERE idproduk='$idproduk'");
        $updatenya = mysqli_query($conn, "UPDATE produk_keluar set qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
        if ($kurangistoknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stokskrg + $selisih;
        $kurangistoknya = mysqli_query($conn, "UPDATE stok_produk SET stok='$kurangin' WHERE idproduk='$idproduk'");
        $updatenya = mysqli_query($conn, "UPDATE produk_keluar set qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");
        if ($kurangistoknya && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}

// menghapus produk keluar
if (isset($_POST['deleteprodukkeluar'])) {
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];
    $idk = $_POST['idkeluar'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE idproduk='$idproduk'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "UPDATE stok_produk set stok='$selisih' WHERE idproduk='$idproduk'");
    $hapusdata = mysqli_query($conn, "DELETE FROM produk_keluar WHERE idkeluar='$idk'");


    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}

// menambah admin baru
if (isset($_POST['addadmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) values ('$email', '$password')");

    if ($queryinsert) {
        // if berhasil
        header('location:admin.php');
    } else {
        // kalau gagal insert ke db
        header('location:admin.php');
    }
}

// edit data admin
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE iduser='$idnya'");

    if ($queryupdate) {
        header('location:admin.php');
    } else {
        header('location:admin.php');

    }
}

// hapus admin
if (isset($_POST['deleteadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE FROM login WHERE iduser='$id'");

    if ($querydelete) {
        header('location:admin.php');
    } else {
        header('location:admin.php');

    }
}


?>