<?php
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Stock Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <!-- Tambahkan script jQuery untuk memanggil API Flask -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(2.5);
            transition: 0.3s ease;
        }

        a {
            text-decoration: black;
            color: black;
        }

        #interactive {
            width: 100%;
            height: 300px;
            margin-top: 10px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">NutriManageLite</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="stok.php"><i class="bi bi-bag"></i> Stock Item</a>
                        <a class="nav-link" href="masuk.php"><i class="bi bi-cloud-arrow-down-fill"></i> Incoming Product</a>
                        <a class="nav-link" href="keluar.php"><i class="bi bi-cloud-arrow-up-fill"></i> Exit Product</a>
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Stock Produk</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalAddProduk">Add Product</button>
                            <a href="export.php" class="btn btn-info">Export Data</a>
                            <!-- <button type="button" class="btn btn-success mr-2" onclick="startScanner()">Scan Barcode</button> -->
                            <!-- <a href="/detect" class="btn btn-success mr-2">Scan Barcode</a> -->
                            <button id="scanJumlahProduk" class="btn btn-success"> <i class="fas fa-camera"></i></button>
                        </div>


                        <div class="card-body">
                            <?php
                            $ambildatastok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE stok < 1");
                            while ($fetch = mysqli_fetch_array($ambildatastok)) {
                                $produk = $fetch['nama_produk'];
                            ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Perhatian!</strong> Stok <?= $produk; ?> telah Habis
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="mauexport" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Picture</th>
                                            <th>Name Item</th>
                                            <th>Description</th>
                                            <th>Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stok_produk");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                                            $namaproduk = $data['nama_produk'];
                                            $deskripsi = $data['deskripsi'];
                                            $stok = $data['stok'];
                                            $idproduk = $data['idproduk'];
                                            $gambar = $data['image'];
                                            $image = $gambar ? '<img src="image/' . $gambar . '" class="zoomable">' : "No Photo";
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $image; ?></td>
                                                <td><?= $namaproduk; ?></td>
                                                <td><?= $deskripsi; ?></td>
                                                <td><?= $stok; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idproduk; ?>">Edit</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idproduk; ?>">Delete</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="edit<?= $idproduk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title text-black">Edit Produk</h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                                <input type="file" name="file" class="form-control">
                                                                <br>
                                                                <input type="text" name="namaproduk" value="<?= $namaproduk; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required>
                                                                <br>
                                                                <input type="number" name="stok" value="<?= $stok; ?>" class="form-control" required>
                                                                <br>
                                                                <button type="submit" class="btn btn-primary" name="updateproduk">Update</button>
                                                            </div>
                                                        </form>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="delete<?= $idproduk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title text-black">Hapus Produk</h4>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus produk <?= $namaproduk; ?>?
                                                                <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusproduk">Hapus</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="ModalAddProduk">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-black">Tambah Produk</h4>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="file" name="file" class="form-control" required>
                        <br>
                        <!-- <input type="text" id="barcode" name="barcode" placeholder="Barcode" class="form-control" required readonly>
                        <br> -->
                        <input type="text" id="namaproduk" name="namaproduk" placeholder="Nama produk" class="form-control" required>
                        <br>
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                        <br>
                        <input type="number" name="stok" placeholder="Stok" class="form-control" required>
                        <br>
                        <div class="d-flex justify-content-between">
                            <!-- <button type="button" class="btn btn-success mr-2" onclick="startScanner()">Scan Barcode</button>
                            <a href="/detect" class="btn btn-success mr-2">Scan Barcode</a>-->
                            <button type="submit" class="btn btn-primary" name="addnewproduk">Submit</button>
                        </div>
                        <div id="interactive" class="viewport" style="display:none;"></div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#scanJumlahProduk').click(function() {
                // Panggil API Flask untuk mendeteksi jumlah produk
                $.ajax({
                    url: 'http://127.0.0.1:5000/detect', // Endpoint API Flask
                    type: 'GET', // Gunakan GET jika API Flask mengembalikan jumlah produk melalui request GET
                    success: function(response) {
                        alert('Jumlah produk yang terdeteksi: ' + response.jumlah_produk);
                    },
                    error: function() {
                        alert('Gagal memindai jumlah produk. Coba lagi nanti.');
                    }
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        function startScanner() {
            document.getElementById('interactive').style.display = 'block';

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#interactive'),
                    constraints: {
                        facingMode: "environment"
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    alert("Scanner gagal diinisialisasi");
                    return;
                }
                Quagga.start();
            });
            // tes
            // $(document).ready(function() {
            //     $('#scanJumlahProduk').click(function() {
            //         $.ajax({
            //             url: 'http://localhost:5000/detect', // URL endpoint API Flask
            //             type: 'POST',
            //             success: function(response) {
            //                 if (response.success) {
            //                     alert('Deteksi berhasil:\n' + response.output);
            //                 } else {
            //                     alert('Error saat menjalankan deteksi: ' + response.error);
            //                 }
            //             },
            //             error: function() {
            //                 alert('Gagal terhubung ke server deteksi.');
            //             }
            //         });
            //     });
            // });

            Quagga.onDetected((result) => {
                const code = result.codeResult.code;
                document.getElementById('barcode').value = code;

                fetch(`get_product.php?barcode=${code}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('namaproduk').value = data.nama_produk;
                        } else {
                            alert('Produk tidak ditemukan');
                        }
                    })
                    .catch(error => console.error('Error:', error));

                Quagga.stop();
                document.getElementById('interactive').style.display = 'none';
            });
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            // Handle form submission here
        });
    </script>
</body>

</html>