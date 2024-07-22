<?php
session_start();
if (isset($_SESSION['is_login'])) {
    header('location: dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Psikotes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully responsive admin theme which can be used to build CRM, CMS,ERP etc." name="description" />
    <meta content="Techzaa" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/my-logo.png">

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="assets/images/my-auth-img.png" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <div class="auth-brand p-4">
                                        <a href="index.php" class="logo-light">
                                            <img src="assets/images/my-logo-dash.png" alt="logo" height="22">
                                        </a>
                                        <a href="index.php" class="logo-dark">
                                            <img src="assets/images/my-logo-dash.png" alt="dark logo" height="22">
                                        </a>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="fs-20">Sign In</h4>
                                        <p class="text-muted mb-3">Silahkan Login Sebelah Sini.
                                        </p>

                                        <!-- form -->
                                        <form id="form-login">
                                            <div class="mb-3">
                                                <label for="emailaddress" class="form-label">Email / Username</label>
                                                <input class="form-control" type="text" name="emailusername" id="emailusername" required="" placeholder="masukkan email / username ..." autofocus>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" type="password" name="password" required="" id="password" placeholder="masukkan password ...">
                                            </div>
                                            <div class="mb-0 text-start">
                                                <button class="btn btn-soft-primary w-100" type="submit"><i class="ri-login-circle-fill me-1"></i> <span class="fw-bold">Log
                                                        In</span> </button>
                                            </div>

                                            <div class="text-center mt-4">
                                                <p class="text-dark-emphasis">Belum Punya Akun? <a href="register.php" class="text-dark fw-bold ms-1 link-offset-3 text-decoration-underline"><b>Register Disini</b></a>
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
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            <script>
                document.write(new Date().getFullYear())
            </script> © Sistem Psikotes - by <b>Hany</b>
        </span>
    </footer>
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Plugin -->
    <script src="assets/vendor/sweetalert2/sweetalert2@11.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#form-login').submit(function(e) {
                e.preventDefault();
                let data = $(this).serializeArray();
                let send = {};
                $.each(data, function(i, field) {
                    send[field.name] = field.value;
                });
                send['action'] = 'login';
                $.ajax({
                    url: 'classes/Authentication.php',
                    type: 'POST',
                    data: send,
                    success: function(response) {
                        let res = JSON.parse(response);
                        Swal.fire({
                            icon: res.status,
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (res.status == "success") {
                                window.location.href = "dashboard.php";
                            } else {
                                location.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>