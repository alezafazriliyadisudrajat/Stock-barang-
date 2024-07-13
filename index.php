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
        <title>Stock Barang</title>
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
            <a class="navbar-brand" href="index.php">ZAA Mart</a>
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
                        <h1 class="mt-4">Stock Barang</h1>
                        <!-- <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol> -->
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                  <!-- Button to Open the Modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                        Tambah Barang
                                    </button>
                                    <a href="export.php" class="btn btn-info">Export</a>
                            </div>
                            <div class="card-body">

                            <?php
                                $getdata = mysqli_query($conn, "SELECT * FROM stock WHERE stock < 1");

                                while($fetch=mysqli_fetch_array($getdata)){
                                    $barang = $fetch['nama_barang'];
                                
                                
                            ?>

                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Mohon maaf!</strong> stok barang <?=$barang;?> ini telah habis.
                                </div>

                            <?php
                                };
                            ?>
                            


                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Gambar</th>
                                                <th>Nama Barang</th>
                                                <th>Deskripsi</th>
                                                <th>Stock</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                                $getdata = mysqli_query($conn, "SELECT * FROM stock");
                                                $i = 1;
                                                while($data= mysqli_fetch_array($getdata)){
                                                $nama_barang = $data['nama_barang'];
                                                $deskripsi = $data['deskripsi'];
                                                $stock = $data['stock'];
                                                $idb = $data['id_barang'];

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
                                                <td><?=$i++;?></td>
                                                <td><?=$img;?></td>
                                                <td><?= $nama_barang;?></td>
                                                <td><?= $deskripsi;?></td>
                                                <td><?= $stock;?></td>
                                                <td>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit<?=$idb;?>">Edit</button>

                                                    <input type="hidden" name="idbaranghps" value="<?= $idb;?>">
                                                    <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idb?>">Delete</button>
                                                </div>
                                                </td>
                                            </tr>

                                              <!-- Edit Modal -->
                                                <div class="modal fade" id="edit<?=$idb;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                        <h4 class="modal-title"> Edit Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <!-- Modal body -->
                                                        <form method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <label class="form-label mt-2">Nama Barang</label>
                                                                <input type="text" name="nama_barang" value="<?=$nama_barang;?>" placeholder="nama barang" class="form-control" required>

                                                                <label class="form-label mt-2">Deskripsi</label>
                                                                <input type="text" value="<?=$deskripsi;?>" name="deskripsi" placeholder="deskripsi barang" class="form-control mb-2" required>

                                                                <label class="form-label mt-2">Gambar</label>
                                                                <input type="file" name="img" class="form-control mb-2">
                                                              
                                                                <input type="hidden" name="id_barang" value="<?=$idb;?>">
                                                                <button type="submit" class="btn btn-success" name="update">Submit</button>
                                                            </div>
                                                        </form>


                                                        
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- End edit modal -->

                                                 <!-- Delete Modal -->
                                                 <div class="modal fade" id="delete<?=$idb;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                        <h4 class="modal-title"> Hapus Barang</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <!-- Modal body -->
                                                        <form action="" method="post">
                                                            <div class="modal-body">
                                                            <h5 class="text-center"> Apakah anda yakin ingin menghapus data ini? <br>
                                                                <span class="text-danger"><?= $nama_barang?></span>
                                                                <input type="hidden" name="id_barang" value="<?=$idb;?>">
                                                            </h5>
                                                            <br>
                                                            <br>
                                                            <button type="submit" class="btn btn-success" name="delete">Submit</button>
                                                            </div>
                                                        </form>
                                                        
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- end delete -->
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
            <h4 class="modal-title">Form Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                <label class="form-label mt-2">Nama Barang</label>
                <input type="text" name="nama_barang" placeholder="nama barang" class="form-control" required>

                <label class="form-label mt-2">Deskripsi</label>
                <input type="text" name="deskripsi" placeholder="deskripsi barang" class="form-control" required>

                <label class="form-label mb-2">Stock</label>
                <input type="number" name="stock" class="form-control mt-2" required>

                <label class="form-label mb-2">Gambar</label>
                <input type="file" name="img" class="form-control mb-2" required>

                <button type="submit" class="btn btn-success" name="addbarang">Submit</button>
                </div>
            </form>
            
        </div>
        </div>
    </div>
</html>
