<?php
include 'koneksi.php';
session_start();

// Memeriksa apakah pengguna belum login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Memeriksa apakah pengguna memiliki level admin atau super admin
if ($_SESSION['level'] != 'admin' && $_SESSION['level'] != 'superadmin') {
  echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
  exit();
}

$i = $_GET['i'] ?? null;

// Halaman admin yang hanya dapat diakses setelah login
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <!-- <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="images/logo.svg" class="mr-2"
                        alt="logo" /></a> -->
                <p>SMP NEGERI 3 SUNGAI PENUH</p>
                <!-- <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg"
                        alt="logo" /></a> -->
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <!-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button> -->
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                                aria-label="search" aria-describedby="search">
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown">
                        <p><?= $_SESSION['level'] ?></p>

                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="images/faces/face28.jpg" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="logout.php">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                    <i class="settings-close ti-close"></i>
                    <p class="settings-heading">SIDEBAR SKINS</p>
                    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                        <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                    </div>
                    <div class="sidebar-bg-options" id="sidebar-dark-theme">
                        <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                    </div>
                    <p class="settings-heading mt-2">HEADER SKINS</p>
                    <div class="color-tiles mx-0 px-4">
                        <div class="tiles success"></div>
                        <div class="tiles warning"></div>
                        <div class="tiles danger"></div>
                        <div class="tiles info"></div>
                        <div class="tiles dark"></div>
                        <div class="tiles default"></div>
                    </div>
                </div>
            </div>
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item <?php echo ($i == null) ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php">
                            <i class="ti-home menu-icon"></i>
                            <span class="menu-title">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'pengguna') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=pengguna">
                            <i class="ti-user menu-icon"></i>
                            <span class="menu-title">Data Pengguna</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'kriteria') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=kriteria">
                            <i class="ti-settings  menu-icon"></i>
                            <span class="menu-title">Data Kriteria</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'ekskul') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=ekskul">
                            <i class="ti-star menu-icon"></i>
                            <span class="menu-title">Data Ekskul</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'siswa') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=siswa">
                            <i class="ti-face-smile menu-icon"></i>
                            <span class="menu-title">Data Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'alternatif') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=alternatif">
                            <i class="ti-layers menu-icon"></i>
                            <span class="menu-title">Data Alternatif</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'SMART') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=SMART">
                            <i class="ti-pulse menu-icon"></i>
                            <span class="menu-title">Proses SMART</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($i == 'hasilakhir') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php?i=hasilakhir">
                            <i class="ti-agenda menu-icon"></i>
                            <span class="menu-title">Hasil Akhir</span>
                        </a>
                    </li>

                    <!-- <li class="nav-item">
            <a class="nav-link" href="index.php?i=user">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">User</span>
            </a>
          </li> -->


                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <?php



          switch ($i) {
            case 'kriteria':
              include 'kriteria.php';
              break;
            case 'pengguna':
              include 'pengguna.php';
              break;
            case 'ekskul':
              include 'ekskul.php';
              break;
            case 'siswa':
              include 'siswa.php';
              break;
            case 'alternatif':
              include 'alternatif.php';
              break;
            case 'SMART':
              include 'hitung.php';
              break;
            case 'hasilakhir':
              include 'hasilakhir.php';
              break;

            case null:
            default:
              include 'dashboard.php';
              break;
          }

          ?>

                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">SMP NEGERI 3 SUNGAI
                            PENUH</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted <i
                                class="ti-heart text-danger ml-1"></i> by Muhamad Haiqal</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
</body>

</html>