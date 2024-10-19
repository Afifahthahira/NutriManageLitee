<?php
require 'function.php';
require 'cek.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courasel</title>
    <link rel="stylesheet" href="home/style.css">
</head>

<body>
    <nav>
        <div class="left">
            <img src="home/img/logo.jpg" alt="Logo">
        </div>
    </nav>

    <div class="container">
        <div class="slides">
            <div class="slide">
                <img src="home/img/p1.jpg" alt="">
            </div>
            <div class="slide">
                <img src="home/img/p2.jpg" alt="">
            </div>

            <div class="slide">
                <img src="home/img/p3.jpg" alt="">
            </div>
            <div class="slide">
                <img src="home/img/p4.jpg" alt="">
            </div>
            <div class="slide">
                <img src="home/img/p5.jpg" alt="">
            </div>
            <div class="navigation">
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
        </div>

        <!-- Judul -->
        <div class="card mt-4 border-0 bg-light">
            <div class="card-body text-center">
                <h5 class="card-title display-3">NutriManageLite - Aplikasi Persedian Produk Herbalife</h5>
                <a href="stok.php" class="btn btn-primary text-center">Lihat Produk</a>
            </div>
        </div>
        <!-- Akhir Judul -->

        <script>
            var slideIndex = 1;
            showSlides(slideIndex);

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("slide");
                if (n > slides.length) {
                    slideIndex = 1;
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slides[slideIndex - 1].style.display = "block";
            }
        </script>


</body>

</html>