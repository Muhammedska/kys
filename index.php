<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Walle</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
</head>

<body id="page-top">
    <div id="">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid">
                        <div class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            WALLE
                        </div>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">

                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow">
                                    <a class="nav-link" href="./login.html">
                                        <span class="d-lg-inline mr-2 text-gray-600 small">Login</span>

                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <section class="container-fluid py-0">
                        <div class="row">
                            <div class="col-md-8 mb-3 bg-light shadow">
                                <div id="demo" class="carousel slide" data-ride="carousel">

                                    <!-- Indicators -->
                                    <ul class="carousel-indicators">
                                        <?php
                                        $examdir = "./src/img/carousel/";
                                        $folders = scandir($examdir);
                                        sort($folders);
                                        array_diff($folders, array('.', '..'));
                                        for ($i = 0; $i < count($folders); $i++) {
                                            if ($folders[$i] != "." && $folders[$i] != "..") {
                                                echo '<li data-target="#demo" data-slide-to="' . $i . '" class="'.(($i == 0) ? 'active' : '' ).'"></li>';
                                            }
                                        }
                                        ?>
                                        
                                    </ul>
                                    <div class="carousel-inner">
                                        <?php
                                        
                                        for ($i = 0; $i < count($folders); $i++) {
                                            if ($folders[$i] != "." && $folders[$i] != "..") {

                                                echo '<div style="max-height: 500px;width:auto;" class="carousel-item ' . ($i == 0 ? 'active' : '') . '">';
                                                echo '<img src="' . $examdir . $folders[$i] . '" class="d-block w-100" alt="...">';
                                                echo '</div>';
                                            }
                                        }
                                        ?>

                                    </div>

                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#demo" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>

                                </div>
                            </div>
                            <div class="col-md-4 mb-3 shadow"> lorem</div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Walle 2022 & BY: Çözelti Software</span></div>
            </div>
        </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/chart.min.js"></script>
    <script src="/assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="/assets/js/theme.js"></script>
</body>

</html>