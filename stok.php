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
                        <h1 class="mt-4 mb-4">Stock Produk</h1>

                    <div class="card mb-4">
                        <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#ModalAddProduk">
                                Add Product
                            </button>
                            <a href="export.php" class="btn btn-info">Export Data</a>
                        </div>
                        <div class="card-body">

                            <?php
                            $ambildatastok = mysqli_query($conn, "SELECT * FROM stok_produk WHERE stok < 1");
                            while ($fetch = mysqli_fetch_array($ambildatastok)) {
                                $produk = $fetch['nama_produk'];
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Perhatian!</strong> Stok
                                    <?= $produk; ?> telah Habis
                                </div>

                                <?php
                            }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="mauexport" width="100%" cellspasing="0">
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
                                                    <?= $i++; ?>
                                                </td>
                                                <td>
                                                    <?= $image; ?>
                                                </td>
                                                <td>
                                                    <?= $namaproduk; ?>
                                                </td>
                                                <td>
                                                    <?= $deskripsi; ?>
                                                </td>
                                                <td>
                                                    <?= $stok; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#edit<?= $idproduk; ?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#delete<?= $idproduk; ?>">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idproduk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Product</h4>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="file" name="file" class="form-control">
                                                                <br>
                                                                <input type="text" name="nama_produk"
                                                                    value="<?= $namaproduk; ?>" class="form-control"
                                                                    required>
                                                                <br>
                                                                <input type="text" name="deskripsi"
                                                                    value="<?= $deskripsi; ?>" class="form-control"
                                                                    required>
                                                                <br>
                                                                <input type="hidden" name="idproduk"
                                                                    value="<?= $idproduk; ?>">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="updateproduk">Submit</button>
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
                                            <div class="modal fade" id="delete<?= $idproduk; ?>">
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
                                                                <br>
                                                                <input type="hidden" name="idproduk"
                                                                    value="<?= $idproduk; ?>">
                                                                <button type="submit" class="btn btn-danger"
                                                                    name="deleteproduk">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                        ;



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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery-dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
</body>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="ModalAddProduk">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-black">Tambah Produk</h4>
            </div>

            <!-- Modal body -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="file" class="form-control" required>
                    <br>
                    <input type="text" name="namaproduk" placeholder="Nama produk" class="form-control" required>
                    <br>
                    <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                    <br>
                    <input type="number" name="stok" placeholder="Stok" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewproduk">Submit</button>
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