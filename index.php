<?php
class DbConnecter extends SQLite3
{
    function __construct($path)
    {
        $this->open($path);
    }
}
if (empty($_COOKIE['sessionisopened'])) {
    $_COOKIE['sessionisopened'] = 'false';
}
$t = 'login.html';
if ($_COOKIE['sessionisopened'] == 'true') {
    session_start();
    $sq = true;
    if ($_COOKIE['type'] == 'student') {
        $t = '/user/user.php';
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = $_COOKIE['type'];
        $_SESSION['userid'] = $_COOKIE['userid'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['graduate'] = $_COOKIE['graduate'];
        $_SESSION['pp'] = $_COOKIE['pp'];
    } else if ($_COOKIE['type'] == 'teacher') {
        $t = '/teacher/user.php';
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = $_COOKIE['type'];
        $_SESSION['userid'] = $_COOKIE['userid'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['subject'] = $_COOKIE['subject'];
        $_SESSION['pp'] = $_COOKIE['pp'];
    } else if ($_COOKIE['type'] == 'kurum') {
        $t = '/admin/admin.php';
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = $_COOKIE['type'];
        $_SESSION['userid'] = $_COOKIE['userid'];
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['graduate'] = $_COOKIE['graduate'];
        $_SESSION['pp'] = $_COOKIE['pp'];
    }
} else {
    $sq = false;
}

$db = new DbConnecter('./src/database/users.db');
$sql = "SELECT * FROM app WHERE var='name'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$corp = strtoupper($row[1]);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Walle</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/chart.min.js"></script>
    <script src="/assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="/assets/js/theme.js"></script>
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">

</head>

<body id="page-top">
    <div id="">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid">
                        <div class="form-inline d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <?php echo $corp ?>
                        </div>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">

                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item no-arrow">
                                <div class="nav-item ">
                                    <?php
                                    if ($sq == true) {

                                        echo '<a class="nav-link"  href="' . $t . '">
                                        <span class=" d-lg-inline mr-2 text-gray-600 small">' . $_SESSION['username'] . '</span>
                                        <img class="border rounded-circle img-profile " src="' . $_SESSION['pp'] . '">
                                    </a>';
                                    } else {
                                        echo '<a class="nav-link" href="./login.html">
                                        <span class="d-lg-inline mr-2 text-gray-600 small">Login</span>

                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>';
                                    }

                                    ?>

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <section class="container-fluid py-0 my-4">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php
                                $examdir = "./src/img/carousel/";
                                $folders = scandir($examdir);
                                $imlip = [];
                                for ($i = 0; $i < count($folders); $i++) {
                                    if ($folders[$i] == '.') {
                                    } else if ($folders[$i] == '..') {
                                    } else if ($folders[$i] == '...') {
                                    } else {
                                        array_push($imlip, $folders[$i]);
                                    }
                                }

                                $diffar = $imlip;
                                //var_dump($diffar);
                                for ($i = 0; $i < count($diffar); $i++) {
                                    if (($diffar == '.') && ($diffar == '..') && ($diffar == '...')) {
                                    } else {
                                        $isac = ($i == 0) ? 'active' : '';
                                        echo '<li data-target="#myCarousel" data-slide-to="' . $i . '" class="' . $isac . '"></li>';
                                    }
                                }
                                ?>
                            </ul>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner text-center bg-dark" style="min-height:274px;height:fit-content;max-height:600px; width:100%;">
                                <?php

                                for ($i = 0; $i < count($diffar); $i++) {
                                    if (($diffar == '.') && ($diffar == '..') && ($diffar == '...')) {
                                    } else {
                                        $isac = ($i == 0) ? 'active' : '';
                                        $biga = strtoupper(str_replace(['.jpeg', '.png', '.jpg'], '', $diffar[$i]));
                                        echo '<div class="carousel-item align-center ' . $isac . '" style="position:relative; width:100%; margin:auto">
                                                <img src="' . $examdir . $diffar[$i] . '"  style="min-height:279px;height:fit-content;max-height:600px;position:relative">
                                                <div class="carousel-caption">
                                                    <h3>' . $biga . '</h3>                                                    
                                                </div>
                                            </div>';
                                    }
                                }
                                ?>

                            </div>

                            <!-- Left and right controls -->
                            <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </section>
                    <section class="container-fluid py-0">
                        <div class="row">
                            <div class="col-md-8 mb-3 bg-light shadow ">


                            </div>
                            <div class="col-md-4 mb-3 shadow">
                                <div>
                                    <h4>Duyurular</h4>
                                </div>
                                <?php
                                $sql = "SELECT * FROM notify ORDER BY notify";
                                $results = $db->query($sql);
                                $notify = [];
                                while ($row = $results->fetchArray()) {
                                    if ($row['mass'] == 'all') {

                                        array_push($notify, array($row['mass'], $row['notify'], $row['sender']));
                                    }
                                };
                                ?>

                                <?php
                                if (count($notify) == 0) {
                                    echo '<div class="text-center">
                                            <h4 class="text-muted">Bildirim Yok</h4>
                                        </div>';
                                } else {
                                    foreach ($notify as $key => $value) {
                                        if ($value[2] == "kurum") {
                                            echo "<div class='media'>                                                    
                                                            <div class='media-body p-4'>
                                                                <h4>{$value[2]}</h4>
                                                                <p>{$value[1]}</p>
                                                            </div>
                                                          </div>";
                                        } else {
                                        }
                                    }
                                }

                                ?>


                            </div>
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


</body>

</html>