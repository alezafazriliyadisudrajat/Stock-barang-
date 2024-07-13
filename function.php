<?php

    session_start();

    // Membuat koneksi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_barang";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // cek koneksi
    if (!$conn) {
        die("Koneksi gagal: ". mysqli_connect_error());
    }

    // menambah data barang baru ke dalam table stock
    if(isset($_POST['addbarang'])){
        $nama_barang = $_POST['nama_barang'];
        $deskripsi = $_POST['deskripsi'];
        $stock = $_POST['stock'];

        // Mengambil nama file gambar
        $allowed_extension = array('png', 'jpg');
        $nama = $_FILES['img']['name']; 
        $dot = explode(".", $nama);
        $ekstensi = strtolower(end($dot)); // Mengambil ekstensi file
        $ukuran = $_FILES['img']['size']; // Mengambil ukuran file
        $file_tmp = $_FILES['img']['tmp_name']; // Mengambil lokasi sementara file

        // Penamaan file dengan enkripsi
        $img = md5(uniqid($nama, true) . time()) . '.' . $ekstensi;

        // validasi udah ada atau belum
        $cek = mysqli_query($conn, "SELECT * FROM stock WHERE nama_barang = '$nama_barang'");
        $hitung = mysqli_num_rows($cek);

        // Mengecek jika belum ada
        if ($hitung < 1) {
            // Proses upload gambar
            if (in_array($ekstensi, $allowed_extension) === true) {
                // Validasi ukuran filenya
                if ($ukuran < 15000000) { // 15MB
                    if (move_uploaded_file($file_tmp, 'img/' . $img)) {
                        // Masukkan data ke tabel
                        $addtotable = mysqli_query($conn, "INSERT INTO stock (nama_barang, deskripsi, stock, img) VALUES ('$nama_barang', '$deskripsi', '$stock', '$img')");
                        if ($addtotable) {
                            echo "<script>alert('Berhasil menambahkan gambar!');
                                document.location='index.php';
                            </script>";
                        } else {
                            echo "<script>alert('Gagal menambahkan gambar!');
                                document.location='index.php';
                            </script>";
                        }
                    } else {
                        echo "<script>alert('Gagal mengunggah gambar!');
                            document.location='index.php';
                        </script>";
                    }
                } else {
                    echo "<script>alert('Ukuran gambar terlalu besar!');
                        document.location='index.php';
                    </script>";
                }
            } else {
                echo "<script>alert('Ekstensi file gambar yang Anda pilih salah!');
                    document.location='index.php';
                </script>";
            }
        }



        $add = mysqli_query($conn, "INSERT INTO stock (nama_barang, deskripsi, stock) VALUES('$nama_barang', '$deskripsi', '$stock')");
        if($add){
            echo "<script>alert( 'Data barang masuk berhasil di tambahkan!');
                    document.location='index.php';
                  </script>";
        }else{
            echo "<script>alert( 'Gagal menambahkan data barang masuk!');
                    document.location='index.php';
                  </script>";
        }
    }

    // menambah barang ke dalam table masuk
    if(isset($_POST['barangmasuk'])){
        $barang = $_POST['barang'];
        $penerima = $_POST['penerima'];
        $jml = $_POST['jml'];

        // cek apakah id_barang berada didalam table stock
        $cekstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang = '$barang'");
        // dapatkan data dari table stock jika ada
        $getdata = mysqli_fetch_array($cekstock);

        $stocknow = $getdata['stock'];
        // lalu tambahkan data 
        $addstocknowtojml = $stocknow + $jml;

        // menambah barang ke dalam tabel masuk
        $masuk = mysqli_query($conn, "INSERT INTO masuk (id_barang, keterangan, jml) VALUES('$barang', '$penerima', '$jml')");
        // update stock barang ke dalam table stock berdasarkan id_barang
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$addstocknowtojml' WHERE id_barang='$barang'");

        if($masuk && $updatestock){
            echo "<script>alert( 'Data barang berhasil di tambahkan!');
                    document.location='masuk.php';
                  </script>";
        }else{
            echo "<script>alert( 'Gagal menambahkan data barang!');
                    document.location='masuk.php';
                  </script>";
        }
    }

    // menambah barang ke dalam table keluar
    if(isset($_POST['barangkeluar'])){
        $barang = $_POST['barang'];
        $penerima = $_POST['penerima'];
        $jml = $_POST['jml'];

        // cek berdasarkan id_barang
        $cekstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang = '$barang'");
        // lalu data diambil 
        $getdata = mysqli_fetch_array($cekstock);

        $stocknow = $getdata['stock'];

        if($stocknow >= $jml){
            // Kalau barang nya cukup
            $addstocknowtojml = $stocknow - $jml;

            // menambah barang ke dalam tabel masuk
            $keluar = mysqli_query($conn, "INSERT INTO keluar (id_barang, penerima, jml) VALUES('$barang', '$penerima', '$jml')");
            $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$addstocknowtojml' WHERE id_barang='$barang'");

            if($keluar && $updatestock){
                echo "<script>alert( 'Data barang berhasil di tambahkan!');
                        document.location='keluar.php';
                    </script>";
            }else{
                echo "<script>alert( 'Gagal menambahkan data barang!');
                        document.location='keluar.php';
                    </script>";
            }
        } else {
            // kalau barang nya ga cukup
            echo "<script>alert( 'stock barang saat ini tidak mencukupi')
                    document.location='keluar.php';
                 </script>";
        }
    }
    

    // update stock barang
    if(isset($_POST['update'])){
        $idb = $_POST['id_barang'];
        $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $current_img = $_POST['current_img']; // Mengambil gambar saat ini
    
        // Mengambil data file gambar yang baru
        $allowed_extension = array('png', 'jpg');
        $nama = $_FILES['img']['name'];
        $dot = explode(".", $nama);
        $ekstensi = strtolower(end($dot)); // Mengambil ekstensi file
        $ukuran = $_FILES['img']['size']; // Mengambil ukuran file
        $file_tmp = $_FILES['img']['tmp_name']; // Mengambil lokasi sementara file
    
        // Jika tidak ada gambar baru yang diunggah
        if ($ukuran == 0 || $nama == "") {
            // Menggunakan gambar saat ini
            $img = $current_img;
        } else {
            // Jika ada gambar baru yang diunggah
            if (in_array($ekstensi, $allowed_extension) === true) {
                if ($ukuran < 15000000) { // 15MB
                    $img = md5(uniqid($nama, true) . time()) . '.' . $ekstensi;
                    move_uploaded_file($file_tmp, 'img/' . $img);
                } else {
                    echo "<script>alert('Ukuran gambar terlalu besar!');
                        document.location='index.php';
                    </script>";
                    exit();
                }
            } else {
                echo "<script>alert('Ekstensi file gambar yang Anda pilih salah!');
                    document.location='index.php';
                </script>";
                exit();
            }
        }
    
        // Update database
        $update = mysqli_query($conn, "UPDATE stock SET nama_barang='$nama_barang', deskripsi='$deskripsi', img='$img' WHERE id_barang='$idb'");
        if ($update) {
            echo "<script>alert('Data barang berhasil diupdate!');
                document.location='index.php';
            </script>";
        } else {
            echo "<script>alert('Gagal update data barang!');
                document.location='index.php';
            </script>";
        }
    }

    // delete stock barang
    if(isset($_POST['delete'])){
        $idb = $_POST['id_barang'];

        $img = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang = '$idb'");
        $get = mysqli_fetch_array($img);
        $gambar = 'img/' .$get['img'];
        unlink($gambar);


        $delete = mysqli_query($conn, "DELETE FROM stock WHERE id_barang =$idb");

        if($delete){
            echo "<script>alert( 'Data barang berhasil dihapus!');
                    document.location='index.php';
                  </script>";
        }else{
            echo "<script>alert( 'Gagal hapus data barang!');
                    document.location='index.php';
                  </script>";
        }
    }

    // update data barang masuk
    if (isset($_POST['updatemasuk'])) {
        $idb = $_POST['id_barang'];
        $idm = $_POST['id_masuk'];
        $keterangan = $_POST['keterangan'];
        $jml = $_POST['jml'];
        $current_img = $_POST['current_img']; // Mengambil gambar saat ini
    
        // Mengambil data file gambar yang baru
        $allowed_extension = array('png', 'jpg');
        $nama = $_FILES['img']['name'];
        $dot = explode(".", $nama);
        $ekstensi = strtolower(end($dot)); // Mengambil ekstensi file
        $ukuran = $_FILES['img']['size']; // Mengambil ukuran file
        $file_tmp = $_FILES['img']['tmp_name']; // Mengambil lokasi sementara file
    
        // Jika tidak ada gambar baru yang diunggah
        if ($ukuran == 0 || $nama == "") {
            // Menggunakan gambar saat ini
            $img = $current_img;
        } else {
            // Jika ada gambar baru yang diunggah
            if (in_array($ekstensi, $allowed_extension) === true) {
                if ($ukuran < 15000000) { // 15MB
                    $img = md5(uniqid($nama, true) . time()) . '.' . $ekstensi;
                    move_uploaded_file($file_tmp, 'img/' . $img);
                } else {
                    echo "<script>alert('Ukuran gambar terlalu besar!');
                        document.location='index.php';
                    </script>";
                    exit();
                }
            } else {
                echo "<script>alert('Ekstensi file gambar yang Anda pilih salah!');
                    document.location='index.php';
                </script>";
                exit();
            }

            // Update database
            $update = mysqli_query($conn, "UPDATE masuk SET nama_barang='$nama_barang', keterangan='$keterangan', jml='$jml', img='$nama' WHERE id_barang='$idb'");
            if ($update) {
                echo "<script>alert('Data barang berhasil diupdate!');
                    document.location='index.php';
                </script>";
            } else {
                echo "<script>alert('Gagal update data barang!');
                    document.location='index.php';
                </script>";
            }
        }
    
        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang = '$idb'");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stockskrng = $stocknya['stock'];
    
        $jmlskrng = mysqli_query($conn, "SELECT * FROM masuk WHERE id_masuk = '$idm'");
        $jmlnya = mysqli_fetch_array($jmlskrng);
        $jmlskrng = $jmlnya['jml'];
    
        if ($jml > $jmlskrng) {
            $selisih = $jml - $jmlskrng;
            $kurangin = $stockskrng - $selisih;
            $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangin' WHERE id_barang = '$idb'");
            $updatenya = mysqli_query($conn, "UPDATE masuk SET jml = '$jml', keterangan = '$keterangan' WHERE id_masuk = '$idm'");
    
            if ($kuranginstocknya && $updatenya) {
                echo "<script>alert('Data barang masuk berhasil di update!');
                        document.location='masuk.php';
                      </script>";
            } else {
                echo "<script>alert('Gagal update data barang masuk!');
                        document.location='masuk.php';
                      </script>";
            }
        } else {
            $selisih = $jmlskrng - $jml;
            $tambahkan = $stockskrng + $selisih;
            $tambahstock = mysqli_query($conn, "UPDATE stock SET stock = '$tambahkan' WHERE id_barang = '$idb'");
            $updatenya = mysqli_query($conn, "UPDATE masuk SET jml = '$jml', keterangan = '$keterangan' WHERE id_masuk = '$idm'");
    
            if ($tambahstock && $updatenya) {
                echo "<script>alert('Data barang masuk berhasil di update!');
                        document.location='masuk.php';
                      </script>";
            } else {
                echo "<script>alert('Gagal update data barang masuk!');
                        document.location='masuk.php';
                      </script>";
            }
        }
    }

    
    // Hapus barang masuk

    if (isset($_POST['deletemasuk'])) {
        $idb = $_POST['id_barang'];
        $jml = $_POST['jml'];
        $idm = $_POST['id_masuk'];

        // Debugging
        echo "id_barang: $idb<br>";
        echo "jml: $jml<br>";
        echo "id_masuk: $idm<br>";

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
        if ($getdatastock && mysqli_num_rows($getdatastock) > 0) {
            $data = mysqli_fetch_array($getdatastock);
            $stock = $data['stock'];

            $selisih = $stock - $jml;

            $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE id_barang='$idb'");
            $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE id_masuk='$idm'");

            if ($update && $hapusdata) {
                echo "<script>alert('Data barang masuk berhasil dihapus!');
                        document.location='masuk.php';
                    </script>";
            } else {
                echo "<script>alert('Gagal hapus data barang masuk!');
                        document.location='masuk.php';
                    </script>";
            }
        } else {
            echo "<script>alert('Gagal mengambil data stok!');
                    document.location='masuk.php';
                </script>";
        }
    }

    // update data barang keluar
    if (isset($_POST['updatekeluar'])) {
        $idb = $_POST['id_barang'];
        $idk = $_POST['id_keluar'];
        $penerima = $_POST['penerima'];
        $jml = $_POST['jml'];
        $current_img = $_POST['current_img']; // Mengambil gambar saat ini
    
        // Mengambil data file gambar yang baru
        $allowed_extension = array('png', 'jpg');
        $nama = $_FILES['img']['name'];
        $dot = explode(".", $nama);
        $ekstensi = strtolower(end($dot)); // Mengambil ekstensi file
        $ukuran = $_FILES['img']['size']; // Mengambil ukuran file
        $file_tmp = $_FILES['img']['tmp_name']; // Mengambil lokasi sementara file
    
        // Jika tidak ada gambar baru yang diunggah
        if ($ukuran == 0 || $nama == "") {
            // Menggunakan gambar saat ini
            $img = $current_img;
        } else {
            // Jika ada gambar baru yang diunggah
            if (in_array($ekstensi, $allowed_extension) === true) {
                if ($ukuran < 15000000) { // 15MB
                    $img = md5(uniqid($nama, true) . time()) . '.' . $ekstensi;
                    move_uploaded_file($file_tmp, 'img/' . $img);
                } else {
                    echo "<script>alert('Ukuran gambar terlalu besar!');
                        document.location='index.php';
                    </script>";
                    exit();
                }
            } else {
                echo "<script>alert('Ekstensi file gambar yang Anda pilih salah!');
                    document.location='index.php';
                </script>";
                exit();
            }

            // Update database
            $update = mysqli_query($conn, "UPDATE keluar SET nama_barang='$nama_barang', penerima='$penerima', jml='$jml', img='$nama' WHERE id_barang='$idb'");
            if ($update) {
                echo "<script>alert('Data barang keluar berhasil diupdate!');
                    document.location='index.php';
                </script>";
            } else {
                echo "<script>alert('Gagal update data barang keluar!');
                    document.location='index.php';
                </script>";
            }
        }
    
        $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang = '$idb'");
        $stocknya = mysqli_fetch_array($lihatstock);
        $stockskrng = $stocknya['stock'];
    
        $jmlskrng = mysqli_query($conn, "SELECT * FROM keluar WHERE id_keluar = '$idk'");
        $jmlnya = mysqli_fetch_array($jmlskrng);
        $jmlskrng = $jmlnya['jml'];
    
        if ($jml > $jmlskrng) {
            $selisih = $jml - $jmlskrng;
            $kurangin = $stockskrng - $selisih;
            $kuranginstocknya = mysqli_query($conn, "UPDATE stock SET stock = '$kurangin' WHERE id_barang = '$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET jml = '$jml', penerima = '$penerima' WHERE id_keluar = '$idk'");
    
            if ($kuranginstocknya && $updatenya) {
                echo "<script>alert('Data barang keluar berhasil di update!');
                        document.location='keluar.php';
                      </script>";
            } else {
                echo "<script>alert('Gagal update data barang keluar!');
                        document.location='keluar.php';
                      </script>";
            }
        } else {
            $selisih = $jmlskrng - $jml;
            $tambahkan = $stockskrng + $selisih;
            $tambahstock = mysqli_query($conn, "UPDATE stock SET stock = '$tambahkan' WHERE id_barang = '$idb'");
            $updatenya = mysqli_query($conn, "UPDATE keluar SET jml = '$jml', penerima = '$penerima' WHERE id_keluar = '$idk'");
    
            if ($tambahstock && $updatenya) {
                echo "<script>alert('Data barang keluar berhasil di update!');
                        document.location='masuk.php';
                      </script>";
            } else {
                echo "<script>alert('Gagal update data barang keluar!');
                        document.location='masuk.php';
                      </script>";
            }
        }
    }

    
    // Hapus data barang dalam table keluar

    if (isset($_POST['deletekeluar'])) {
        $idb = $_POST['id_barang'];
        $jml = $_POST['jml'];
        $idk = $_POST['id_keluar'];

        // Debugging
        echo "id_barang: $idb<br>";
        echo "jml: $jml<br>";
        echo "id_keluar: $idk<br>";

        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
        if ($getdatastock && mysqli_num_rows($getdatastock) > 0) {
            $data = mysqli_fetch_array($getdatastock);
            $stock = $data['stock'];

            $selisih = $stock + $jml;

            $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE id_barang='$idb'");
            $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE id_keluar='$idk'");

            if ($update && $hapusdata) {
                echo "<script>alert('Data barang masuk berhasil dihapus!');
                        document.location='keluar.php';
                    </script>";
            } else {
                echo "<script>alert('Gagal hapus data barang keluar!');
                        document.location='keluar.php';
                    </script>";
            }
        } else {
            echo "<script>alert('Gagal mengambil data stok!');
                    document.location='masuk.php';
                </script>";
        }
    }


    // add new admin
    if (isset($_POST['addadmin'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $add = mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email', '$password')");

        if($add){
            echo "<script>alert('Admin berhasil ditambahkan!');
                    document.location='admin.php';
                </script>";
        } else{
            echo "<script>alert('Gagal menambahkan admin!');
                    document.location='admin.php';
                </script>";
        }
    }

    //edit data admin
    if(isset($_POST['updateadmin'])){
        $newemail = $_POST['email'];
        $newpw = $_POST['password'];
        $ids = $_POST['id_user'];

        $update = mysqli_query($conn, "UPDATE login SET email='$newemail', password='$newpw' WHERE id_user='$ids'");

        if($update){
            echo "<script>alert('Admin berhasil diubah!');
                    document.location='admin.php';
                </script>";
        } else{
            echo "<script>alert('Gagal mengubah admin!');
                    document.location='admin.php';
                </script>";
        }
    }

    // hapus admin
    if(isset($_POST['deleteadmin'])){
        $ids = $_POST['id_user'];

        $delete = mysqli_query($conn, "DELETE FROM login WHERE id_user='$ids'");

        if($delete){
            echo "<script>alert('Admin berhasil dihapus!');
                    document.location='admin.php';
                </script>";
        } else {
            echo "<script>alert('Gagal menghapus admin!');
                    document.location='admin.php';
                </script>";
        }
    }


?>
