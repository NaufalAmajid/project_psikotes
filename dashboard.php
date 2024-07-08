<?php
date_default_timezone_set('Asia/Jakarta');

require_once 'config/connection.php';
require_once 'config/functions.php';
require_once 'classes/DB.php';
require_once 'classes/Menu.php';

$menu = new Menu();
$func = new Functions();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Psikotest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">

    <!-- Vector Map css -->
    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">


        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="index.html" class="logo-light">
                            <span class="logo-lg">
                                <img src="assets/images/logo.png" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="index.html" class="logo-dark">
                            <span class="logo-lg">
                                <img src="assets/images/logo-dark.png" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="ri-menu-line"></i>
                    </button>

                    <!-- Horizontal Menu Toggle Button -->
                    <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="assets/images/users/avatar-1.jpg" alt="user-image" width="32" class="rounded-circle">
                            </span>
                            <span class="d-lg-block d-none">
                                <h5 class="my-0 fw-normal">Thomson <i class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="pages-profile.html" class="dropdown-item">
                                <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                                <span>Akun Saya</span>
                            </a>

                            <!-- item-->
                            <a href="auth-logout-2.html" class="dropdown-item">
                                <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->


        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="index.html" class="logo logo-light">
                <span class="logo-lg">
                    <img src="assets/images/logo.png" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="index.html" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="assets/images/logo-dark.png" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">Menu</li>

                    <li class="side-nav-item <?= isset($_GET['mydashboard']) ? 'menuitem-active' : '' ?>">
                        <a href="?mydashboard" class="side-nav-link">
                            <i class="ri-dashboard-3-line"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <?php
                    $menus = $menu->read(1);
                    ?>
                    <?php foreach ($menus as $men) : ?>
                        <?php if (isset($men['submenu'])) : ?>
                            <li class="side-nav-item <?= isset($_GET['sub']) && $_GET['page'] == $men['direktori_head'] ? 'menuitem-active' : '' ?>">
                                <a data-bs-toggle="collapse" href="#sidebarMultiLevel" class="side-nav-link">
                                    <i class="<?= $men['icon'] ?>"></i>
                                    <span> <?= ucwords($men['nama_menu']) ?> </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse <?= isset($_GET['sub']) && $_GET['page'] == $men['direktori_head'] ? 'show' : '' ?>" id="sidebarMultiLevel">
                                    <ul class="side-nav-second-level">
                                        <?php foreach ($men['submenu'] as $submenu) : ?>
                                            <li class="<?= isset($_GET['sub']) && $_GET['sub'] == $submenu['direktori'] && $_GET['page'] == $men['direktori_head'] ? 'menuitem-active' : '' ?>">
                                                <a href="?page=<?= $men['direktori_head'] ?>&sub=<?= $submenu['direktori'] ?>" class="<?= isset($_GET['sub']) && $_GET['sub'] == $submenu['direktori'] && $_GET['page'] == $men['direktori_head'] ? 'active' : '' ?>"><?= ucwords($submenu['nama_submenu']) ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php else : ?>
                            <li class="side-nav-item <?= isset($_GET['page']) && $_GET['page'] == $men['direktori'] ? 'menuitem-active' : '' ?>">
                                <a href="?page=<?= $men['direktori'] ?>" class="side-nav-link">
                                    <i class="<?= $men['icon'] ?>"></i>
                                    <span> <?= ucwords($men['nama_menu']) ?> </span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <li class="side-nav-title">Autentikasi</li>
                    <li class="side-nav-item">
                        <a href="javascript:void(0)" class="side-nav-link">
                            <i class="ri-logout-box-line"></i>
                            <span> Logout </span>
                        </a>
                    </li>

                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : '';
                $sub = isset($_GET['sub']) ? $_GET['sub'] : '';
                if ($page == '') {
                    $main = 'pages/main.php';
                    if (!is_file($main)) {
                        echo 'File not found!';
                    } else {
                        include $main;
                    }
                } else {
                    if ($sub == '') {
                        $page = 'pages/' . $page . '.php';
                        if (!is_file($page)) {
                            file_put_contents($page, 'File ' . $_GET['page']);
                            chmod($page, 0777);
                        } else {
                            include $page;
                        }
                    } else {
                        $folder = 'pages/' . $page . '/';
                        if (!is_dir($folder)) {
                            mkdir($folder);
                        }
                        $sub = $folder . $sub . '.php';
                        if (!is_file($sub)) {
                            file_put_contents($sub, 'File ' . $_GET['sub']);
                        } else {
                            include $sub;
                        }
                    }
                }
                ?>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Sistem Psikotes - by <b>Hany</b>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
    <script>
        $(document).ready(function() {
            if (window.location.href.indexOf('page') == -1 && window.location.href.indexOf('mydashboard') == -1) {
                window.location.href = 'dashboard.php?mydashboard';
            }
        });
    </script>

    <!-- Daterangepicker js -->
    <!-- <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script> -->

    <!-- Vector Map js -->
    <!-- <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script> -->

    <!-- Dashboard App js -->
    <!-- <script src="assets/js/pages/dashboard.js"></script> -->


    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>