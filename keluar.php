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
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Stock Item</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(2.5);
            transition: 0.3s ease;
        }

        /* Style untuk frame kamera */
        #interactive {
            width: 100%;
            height: 400px;
            border: 2px solid black;
            object-fit: cover;}
    </style>
</head>


<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">NutriManageLite</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="stok.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-bag"></i></div>
                            Stock Item
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-cloud-arrow-down-fill"></i></div>
                            Incoming Product
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                            Exit Product
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-box-arrow-right"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Produk Keluar</h1>

                    <!-- modal AddExit -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal AddExit -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#ModalAddExitProduk">
                                Add Exit Product
                            </button>

                            <!-- Button to trigger the barcode modal -->
                            <button type="button" class="btn btn-success" id="scanBarcodeBtn" data-toggle="modal" data-target="#ModalScanBarcode">
                                Scan Barcode
                            </button>

                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspasing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Picture</th>
                                            <th>Name Item</th>
                                            <th>Totality</th>
                                            <th>Recipient</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM produk_keluar k, stok_produk s WHERE s.idproduk = k.idproduk");
                                        while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                                            $idk = $data['idkeluar'];
                                            $idproduk = $data['idproduk'];
                                            $tanggal = $data['tanggal'];
                                            $namaproduk = $data['nama_produk'];
                                            $qty = $data['qty'];
                                            $penerima = $data['penerima'];

                                            // cek ada gambar atau tidak
                                            $gambar = $data['image']; // ambil gambar
                                            if ($gambar == null) {
                                                //jika tidak ada gambar
                                                $image = "No Photo";
                                            } else {
                                                //jika ada gambar
                                                $image = '<img src="image/' . $gambar . '" class="zoomable">';
                                            }


                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $tanggal ?>
                                                </td>
                                                <td>
                                                    <?= $image ?>
                                                </td>
                                                <td>
                                                    <?= $namaproduk; ?>
                                                </td>
                                                <td>
                                                    <?= $qty; ?>
                                                </td>
                                                <td>
                                                    <?= $penerima; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#edit<?= $idk; ?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#delete<?= $idk; ?>">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>


                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Product</h4>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="text" name="penerima" value="<?= $penerima; ?>"
                                                                    class="form-control" required>
                                                                <br>
                                                                <input type="number" name="qty" value="<?= $qty; ?>"
                                                                    class="form-control" required>
                                                                <br>
                                                                <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                                <br>
                                                                <input type="hidden" name="idkeluar" value="<?= $idk; ?>">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="updateprodukkeluar">Submit</button>
                                                            </div>
                                                        </form>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Delete Product</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus
                                                                <?= $namaproduk; ?>?
                                                                <br>
                                                                <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                                <br>
                                                                <input type="hidden" name="qty" value="<?= $qty; ?>">
                                                                <br>
                                                                <input type="hidden" name="idkeluar" value="<?= $idk; ?>">
                                                                <button type="submit" class="btn btn-danger"
                                                                    name="deleteprodukkeluar">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        };
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Scan Barcode -->
                    <div class="modal fade" id="ModalScanBarcode" tabindex="-1" role="dialog" aria-labelledby="ModalScanBarcodeLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Scan Barcode</h5>
                                            <div id="interactive" class="viewport"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery-dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>

        <!-- Script QuaggaJS Scanning -->    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol Scan Barcode
    document.getElementById('scanBarcodeBtn').addEventListener('click', function() {
        startScanner(); // Panggil fungsi startScanner saat tombol diklik
    });
});

function startScanner() {
    var scannerContainer = document.getElementById('interactive');
    scannerContainer.style.display = 'block';

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: scannerContainer, // hanya satu target
            constraints: {
                facingMode: "environment", // gunakan kamera belakang
                width: 640, // Sesuaikan lebar video
                height: 480 // Sesuaikan tinggi video
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader", "upc_e_reader", "codabar_reader"]
        }
    }, function(err) {
        if (err) {
            console.log("Quagga init error:", err);
            return;
        }
        Quagga.start(); // Mulai Quagga jika inisialisasi berhasil
    });

    Quagga.onDetected(function(result) {
        var code = result.codeResult.code;
        alert("Barcode detected: " + code); // Menampilkan hasil scan
        document.getElementById('barcode').value = code; // Contoh menampilkan hasil scan ke input

        // Mengirim hasil scan ke keluar.php
        $.ajax({
            url: 'keluar.php',
            type: 'POST',
            data: {
                barcode: code
            },
            success: function(response) {
                alert("Response: " + response);
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });

        Quagga.stop(); // Berhenti scanning setelah barcode terbaca
        scannerContainer.style.display = 'none'; // Sembunyikan tampilan kamera
    });
}
    </script>
</body>

<!-- The Modal -->
<div class="modal fade" id="ModalAddExitProduk">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-black">Tambah Produk Keluar</h4>
            </div>

            <!-- Modal untuk Scan Barcode -->
            <div class="modal fade" id="ModalScanBarcode" tabindex="-1" role="dialog" aria-labelledby="ModalScanBarcodeLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalScanBarcodeLabel">Scan Barcode</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Tampilan kamera -->
                            <div id="interactive" class="viewport" style="width: 300px; height: 400px; border: 2px solid black;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal body -->
            <form method="post">
                <div class="modal-body">

                    <select name="produknya" class="form-control">
                        <?php
                        $ambilsemuadata = mysqli_query($conn, "SELECT * FROM stok_produk");
                        while ($fetcharray = mysqli_fetch_array($ambilsemuadata)) {
                            $namaproduknya = $fetcharray['nama_produk'];
                            $idproduknya = $fetcharray['idproduk'];
                        ?>

                            <option value="<?= $idproduknya; ?>">
                                <?= $namaproduknya; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                    <br>
                    <input type="text" name="penerima" class="form-control" placeholder="Penerima" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addprodukkeluar">Submit</button>
                </div>
            </form>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>