<?php
$currentUrl = $_SERVER['PHP_SELF']; // Mendapatkan URL saat ini

// Parse URL untuk mendapatkan path
$urlParts = parse_url($currentUrl);


// Pecah path menjadi bagian-bagian dengan delimiter '/'
$pathParts = explode('/', $urlParts['path']);

// Hapus elemen kosong dari array
$pathParts = array_filter($pathParts);

// Ambil folder sebelum nama file (indeks kedua dari belakang)
$folder = $pathParts[count($pathParts) -1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $folder?></title>
    <!-- baseurl diambil dari file Constants di folder Core -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Ini adalah contoh CSS untuk menyembunyikan toolbar */
        trix-toolbar {
            display: none; /* Mengatur display toolbar menjadi none untuk menyembunyikannya */
        }
        .input-container {
            display: flex; /* Menggunakan flexbox untuk mengatur elemen dalam satu baris */
            align-items: center; /* Mengatur elemen secara vertikal di tengah */
        }

        .input-container label {
            margin-right: 10px; /* Memberikan jarak antara label dan input */
        }

        .input-container input[type="text"] {
            width: 100px;
        }

        .input-container button {
            margin-left: 10px; /* Memberikan jarak antara input dan tombol "Hapus" */
        }
    </style>
<body data-bs-theme="dark">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="../dashboard"><b>Bookorama</b></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link <?= $folder == 'dashboard' ? "active" : "" ?>" href="../dashboard">Dashboard</a>
        <a class="nav-link <?= $folder == 'books' ? "active" : "" ?>" href="../books">Books</a>
        <a class="nav-link <?= $folder == 'categories' ? "active" : "" ?>" href="../categories">Categories</a>
        <a class="nav-link <?= $folder == 'books_per_category' ? "active" : "" ?>" href="../books_per_category">Books per Categories</a>
        <a class="nav-link <?= $folder == 'customers' ? "active" : "" ?>" href="../customers">Customers</a>
        <a class="nav-link <?= $folder == 'orders' ? "active" : "" ?>" href="../orders">Orders</a>
      </div>
      <?php
        if(isset($_SESSION['email'])){
      ?>
        <div class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Welcome back,  <?= $_SESSION['email']?>
            </a>
            <div class="dropdown-menu row">
                <div class="col-md-auto">
                    <a class="btn btn-secondary" href="../access/logout.php">Logout</a>
                </div>
            </div>
          </li>
        </div>

      <?php
        }
      ?>
      
    </div>
  </div>
</nav>
<div class="container mt-4">

