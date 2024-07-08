<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Register | Velonic - Bootstrap 5 Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden bg-opacity-25">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="assets/images/my-auth-img2.png" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand p-4">
                                        <a href="index.html" class="logo-light">
                                            <img src="assets/images/logo.png" alt="logo" height="22">
                                        </a>
                                        <a href="index.html" class="logo-dark">
                                            <img src="assets/images/logo-dark.png" alt="dark logo" height="22">
                                        </a>
                                    </div>
                                    <div class="p-4 my-auto">
                                        <h4 class="fs-20">Free Register</h4>
                                        <p class="text-muted mb-3">Silahkan Buat Akun Disebelah Sini!</p>

                                        <!-- form -->
                                        <form action="#">
                                            <div class="mb-3">
                                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                                <input class="form-control" type="text" name="nama_lengkap" id="fullname" placeholder="masukkan nama lengkap ..." required="" autofocus>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input class="form-control" type="text" name="username" id="username" placeholder="masukkan username ..." required="">
                                            </div>
                                            <div class="mb-3">
                                                <label for="emailaddress" class="form-label">Email</label>
                                                <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
                                            </div>
                                            <div class="mb-0 d-grid text-center">
                                                <button class="btn btn-primary fw-semibold" type="submit">Daftar</button>
                                            </div>

                                            <div class="text-center mt-4">
                                                <p class="text-dark-emphasis">Sudah Punya Akun? <a href="index.php" class="text-dark fw-bold ms-1 link-offset-3 text-decoration-underline"><b>Log In</b></a>
                                                </p>
                                            </div>
                                        </form>
                                        <!-- end form-->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark-emphasis">
            <script>
                document.write(new Date().getFullYear())
            </script> © Sistem Psikotes - by <b>Hany</b>
        </span>
    </footer>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>