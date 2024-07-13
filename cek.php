<?php
    // jika belum login
    if(isset($_SESSION['log'])){
    
    } else{
        echo "<script>alert( 'Silahkan login terlebih dahulu!');
                document.location='login.php';
              </script>";
    }
?>