<?php

use App\Models\UserModel;

$userModel = new UserModel();
$balance = $userModel->getBalance();
helper('number');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= env('app.name') ?> | <?= $title ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">

    <?= $this->renderSection('header') ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/logout') ?>" role="button" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block"><?= session()->agen_name ?></a>
                        <p class="text-white h5"><?= number_to_currency($balance, 'IDR', 'id-ID') ?></p>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url('/') ?>" class="nav-link <?= $nav_active === "dashboard" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/transaksi') ?>" class="nav-link <?= $nav_active === "transaction" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Transaksi
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/produk') ?>" class="nav-link <?= $nav_active === "product" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Produk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/deposit') ?>" class="nav-link <?= $nav_active === "deposit" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>
                                    Tiket Deposit
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/riwayat-deposit') ?>" class="nav-link <?= $nav_active === "history-deposit" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Riwayat Deposit
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/mutasi') ?>" class="nav-link <?= $nav_active === "mutasi" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Mutasi
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/pesan') ?>" class="nav-link <?= $nav_active === "message" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>
                                    Pesan Keluar/Masuk
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/downline') ?>" class="nav-link <?= $nav_active === "downline" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Downline
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/ganti-password') ?>" class="nav-link <?= $nav_active === "change-password" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    Password & PIN
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('/logout') ?>" class="nav-link <?= $nav_active === "logout" ? 'active' : null ?>">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?= $this->renderSection('content') ?>

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date('Y') === "2022" ? date('Y') : "2022" + date('Y') ?> <a href="#"><?= env('app.name') ?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script> -->
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('dist/js/adminlte.js') ?>"></script>

    <?= $this->renderSection('footer') ?>

</body>

</html>