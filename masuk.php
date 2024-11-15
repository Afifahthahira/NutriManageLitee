#barcode

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="path/to/style.css">
    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(2.5);
            transition: 0.3s ease;
        }
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
                    <h1 class="mt-4 mb-4">Produk Masuk</h1>

                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#ModalAddProdukMasuk">
                                Add Incoming Product
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablemasuk" width="100%" cellspasing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Picture</th>
                                            <th>Name Item</th>
                                            <th>Totality</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM produk_masuk m, stok_produk s where s.idproduk =m.idproduk");
                                        while ($data = mysqli_fetch_array($ambilsemuadatastok)) {
                                            $idproduk = $data['idproduk'];
                                            $idm = $data['idmasuk'];
                                            $tanggal = $data['tanggal'];
                                            $namaproduk = $data['nama_produk'];
                                            $qty = $data['qty'];
                                            $keterangan = $data['keterangan'];

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
                                                    <?= $keterangan; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#edit<?= $idm; ?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#delete<?= $idm; ?>">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idm; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Product</h4>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="text" name="keterangan"
                                                                    value="<?= $keterangan; ?>" class="form-control"
                                                                    required>
                                                                <br>
                                                                <input type="number" name="qty" value="<?= $qty; ?>"
                                                                    class="form-control" required>
                                                                <br>
                                                                <input type="hidden" name="idproduk"
                                                                    value="<?= $idproduk; ?>">
                                                                <br>
                                                                <input type="hidden" name="idmasuk" value="<?= $idm; ?>">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="updateprodukmasuk">Submit</button>
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
                                            <div class="modal fade" id="delete<?= $idm; ?>">
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
                                                                <input type="hidden" name="idproduk"
                                                                    value="<?= $idproduk; ?>">
                                                                <br>
                                                                <input type="hidden" name="qty" value="<?= $qty; ?>">
                                                                <br>
                                                                <input type="hidden" name="idmasuk" value="<?= $idm; ?>">
                                                                <button type="submit" class="btn btn-danger"
                                                                    name="deleteprodukmasuk">Delete</button>
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
    <script src="assets/demo/chart-pie-demo.js"></script>

    <!-- Barcode scanner library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quaggaJS/0.12.1/quagga.min.js"></script>

    <script>
        let barcodeResult = document.getElementById('barcode-result');

        // Function to start barcode scanning
        document.getElementById('start-scanner-btn').addEventListener('click', function() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.getElementById('scanner-container')
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
                }

            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            });

            Quagga.onDetected(function(data) {
                barcodeResult.innerText = data.codeResult.code; // Display scanned barcode
                Quagga.stop(); // Stop scanning after successful read
            });
        });
    </script>
</body>

</html>

<!-- The Modal -->
<div class="modal fade" id="ModalAddProdukMasuk">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-black">Tambah Produk Masuk</h4>
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
                        <?php } ?>
                    </select>
                    <br>
                    <input type="text" id="kode-barcode" name="kode_barcode" class="form-control" placeholder="Kode Barcode" readonly>
                    <br>
                    <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                    <br>
                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="produkmasuk">Submit</button>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalBarcodeScanner">Scan Barcode</button>
                </div>
            </form>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Barcode Scanner -->
<div class="modal fade" id="ModalBarcodeScanner">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Scan Barcode</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div id="scanner-container" style="height: 300px; background-color: #f0f0f0;"></div>
                    <p id="barcode-result" style="margin-top: 20px; text-align: center; font-weight: bold;"></p>
                    <svg id="barcodeDisplay"></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Scanner Barcode -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- Pastikan JsBarcode sudah di-include -->
<script src="https://cdn.rawgit.com/lindell/JsBarcode/master/dist/JsBarcode.all.min.js"></script>

<script>
    // Start Scanner Function
    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-container'),
                constraints: {
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader"] // Tipe barcode
            }
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
            console.log("Quagga started");
        });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;
            console.log("Barcode detected:", code);

            // Masukkan hasil scan ke input field di modal "Tambah Produk Masuk"
            document.getElementById('kode-barcode').value = code;

            // Menampilkan alert atau pesan konfirmasi
            alert('Barcode berhasil dipindai!');
        });
    }

    // Event untuk menghidupkan scanner ketika modal dibuka
    $('#ModalBarcodeScanner').on('shown.bs.modal', function() {
        startScanner();
    });

    // Event untuk mematikan scanner ketika modal ditutup
    $('#ModalBarcodeScanner').on('hidden.bs.modal', function() {
        Quagga.stop();
    });


    function handleDetected(result) {
        var code = result.codeResult.code;
        console.log("Barcode detected:", code);

        // Isi field kode-barcode dengan hasil scan
        document.getElementById('kode-barcode').value = code;

        // Stop Quagga setelah deteksi berhasil
        Quagga.stop();
        alert('Barcode berhasil dipindai!');

        // Hapus event listener setelah barcode berhasil terdeteksi
        Quagga.offDetected(handleDetected);
    }



    // Start Scanner Button Click Event
    document.getElementById('start-scanner-btn').addEventListener('click', function() {
        startScanner();
    });
</script>