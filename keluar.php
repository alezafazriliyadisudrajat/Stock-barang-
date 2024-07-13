<?php
    // panggil koneksi database
    include 'function.php';

    // cek apakah sudah login/belum
    include 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title> Barang Keluar</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .zoomable{
                width: 100px;
            }

            .zoomable:hover{
                transform: scale(2.5);
                transition: 0.3s ease;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="keluar.php">ZAA Mart</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

          
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>

                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Barang Masuk
                            </a>

                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Barang Keluar
                            </a>

                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Kelola Admin
                            </a>

                            <a class="nav-link" href="logout.php">
                                    Logout
                            </a>
                        </div>
                    </div>
                    
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Barang Keluar</h1>
                        <!-- <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol> -->
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                  <!-- Button to Open the Modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        Tambah Barang
                                    </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Gambar</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>Penerima</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php
                                                $getdata = mysqli_query($conn, "SELECT * FROM keluar k, stock s WHERE s.id_barang = k.id_barang");
                                                while($data= mysqli_fetch_array($getdata)){
                                                $idk = $data['id_keluar'];
                                                $idb = $data['id_barang'];
                                                $tanggal = $data['tanggal'];
                                                $nama_barang = $data['nama_barang'];
                                                $jml = $data['jml'];
                                                $penerima = $data['penerima'];

                                                // cek ada gambar atau tidak
                                                $img = $data['img'];
                                                if($img == null){
                                                    // jika tidak ada gambar
                                                    $img = "No Photo";
                                                } else {
                                                    // jika ada gambar
                                                    $img = '<img src="img/' . $img .'" class="zoomable">';
                                                }
                                                ?>
                                            <tr>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$img;?></td>
                                                <td><?= $nama_barang;?></td>
                                                <td><?= $jml;?></td>
                                                <td><?= $penerima;?></td>
                                                <td>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit<?= $idk; ?>">Edit</button>

                                                        <input type="hidden" name="idbaranghps" value="<?= $idb; ?>">
                                                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idk ?>">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>

                                               <!-- Edit Modal -->
                                               <div class="modal fade" id="edit<?= $idk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"> Edit Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form action="" method="post">
                                                            <div class="modal-body">
                                                                <!-- <label class="form-label mt-2">Nama Barang</label>
                                                                <input type="text" name="nama_barang" value="<?= $nama_barang; ?>" placeholder="nama barang" class="form-control" required> -->

                                                                <label class="form-label mt-2">Penerima</label>
                                                                <input type="text" value="<?= $penerima; ?>" name="penerima" placeholder="penerima" class="form-control mb-2" required>

                                                                <label class="form-label mt-2">Jumlah</label>
                                                                <input type="number" value="<?= $jml; ?>" name="jml" placeholder="jml" class="form-control mb-2" required>

                                                                
                                                                <label class="form-label mt-2">Gambar</label>
                                                                <input type="file" name="img" class="form-control mb-2">


                                                                <input type="hidden" name="id_barang" value="<?= $idb; ?>">
                                                                <input type="hidden" name="id_masuk" value="<?= $jml; ?>">
                                                                <button type="submit" class="btn btn-success" name="updatekeluar">Submit</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End edit modal -->

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idk; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"> Hapus Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <h5 class="text-center"> Apakah anda yakin ingin menghapus data ini? <br>
                                                                    <span class="text-danger"><?= $nama_barang ?></span>
                                                                    <input type="hidden" name="id_barang" value="<?= $idb; ?>">
                                                                    <input type="hidden" name="jml" value="<?= $jml; ?>">
                                                                    <input type="hidden" name="id_keluar" value="<?= $idk; ?>">
                                                                </h5>
                                                                <br>
                                                                <br>
                                                                <button type="submit" class="btn btn-success" name="deletekeluar">Submit</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end delete modal -->
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
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
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
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Form Barang keluar</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form action="" method="post">
                <div class="modal-body">
                <label class="form-label">Nama Barang</label>
                    <select name="barang" id="barang" class="form-control mt-2">
                        <?php
                            $getdata = mysqli_query($conn, "SELECT * FROM stock");
                            while($fetcharray = mysqli_fetch_array($getdata)) {
                                $nama_barang = $fetcharray['nama_barang'];
                                $idbarang = $fetcharray['id_barang'];

                        ?>
                        <option value="<?= $idbarang;?>"><?= $nama_barang;?></option>
                        <?php
                            };
                        ?>
                    </select>

                <label class="form-label mt-2">Jumlah</label>
                <input type="number" name="jml" placeholder="jumlah barang" class="form-control" required>

                <label class="form-label mb-2">Penerima</label>
                <input type="text" name="penerima" placeholder="Penerima" class="form-control mb-3" required>

                <button type="submit" class="btn btn-success" name="barangkeluar">Submit</button>
                </div>
            </form>
            
        </div>
        </div>
    </div>
</html>
