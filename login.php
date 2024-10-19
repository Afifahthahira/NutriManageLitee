<?php
require 'function.php';

//Cek login terdaftar atau tidak
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // cocokan dengan database apakah ada atau tidak
    $cekdatabase = mysqli_query($conn, "SELECT * FROM login where email='$email' and password='$password'");
    //hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $_SESSION['log'] = 'True';
        header('location:index.php');
    } else {
        header('location:login.php');
    }
    ;
}
;

if (!isset($_SESSION['log'])) {

} else {
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
    <title>Herbalife</title>
    <link rel="stylesheet" href="loginform/style.css">
</head>
<body>
    <div class="container">
        <div class="login">
            <form method="post">
                <h1>Login</h1>
                <hr>
                <label for="email">Email Address</label>
                <input type="text" id="email" name="email" placeholder="example@gmail.com">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="password">
                <button type="submit" name="login" href="index.php">Login</button>
        </div>
        <div class="right">
            <img src="loginform/img/image.jpg" alt="">
        </div>
    </div>
    </form>
</body>

</html>