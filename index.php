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

$sql = "SELECT * FROM notify WHERE mass = 'all'";
$results = $db->query($sql);
$notify = [];
while ($row = $results->fetchArray()) {
    array_push($notify, $row['notify']);
};

$sql = "SELECT * FROM app WHERE var='name'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$corp = strtoupper($row[1]);

$sql = "SELECT * FROM app WHERE var='carousel'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$carousel = $row[1];

$sql = "SELECT * FROM app WHERE var='notify'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$notshow = $row[1];

$sql = "SELECT * FROM app WHERE var='why1'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$why1 = $row[1];

$sql = "SELECT * FROM app WHERE var='why2'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$why2 = $row[1];

$sql = "SELECT * FROM app WHERE var='why3'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$why3 = $row[1];

$sql = "SELECT * FROM app WHERE var='why4'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$why4 = $row[1];

$sql = "SELECT * FROM app WHERE var='address'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$address = $row[1];

$sql = "SELECT * FROM app WHERE var='phone'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$phone = $row[1];

$sql = "SELECT * FROM app WHERE var='email'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$email = $row[1];

$sql = "SELECT * FROM app WHERE var='map'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$map = $row[1];


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $corp?> ??zel E??itim Kurumu E??itim i??in en iyisi">
    <meta name="keywords" content="Ders, Dershane, E??itim, ??zel Kurs, Vip E??itim, Mersin, Be?? Nokta, ">
    <meta name="author" content="????zelti Software">
    <meta name="designer" content="????zelti Software">
    <meta name="og:image" content="./favicon.svg"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $corp ?></title>
    <link rel="stylesheet" href="/assets/bootstrap/css/maiin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/chart.min.js"></script>
    <script src="/assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="/assets/js/theme.js"></script>
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    <style>
        html {
            scroll-behavior: smooth;
        }


        /* width */
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            backkground-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: grey;
            border-radius: 10px;
        }
    </style>

</head>

<body id="page-top">
    <div class="modal fade" id="notify">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Bildirimler & Duyurular</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <?php
                    if ($notshow == 'active') {
                        foreach (array_reverse($notify) as $not) {
                            echo "<div class='media'>
                            <span class='align-self-center dropdown no-arrow ' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>                            
                                    <i class='align-self-center mr-3 rounded-circle fa fa-bullhorn' style='font-size:60px;'></i>
                            </span>
                            <div class='media-body p-4'>
                                <h4>Genel Duyuru</h4>
                                <p>{$not}</p>
                            </div>
                        </div>";;
                        }
                    }
                    ?>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div id="">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow topbar fixed-top">
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
                                        <img class="border rounded-circle img-profile " height=50 width=50 src="' . $_SESSION['pp'] . '">
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
                <header class="d-flex masthead" style="position:relative;background-image:url('assets/img/bg-masthead.jpg');background-repeat: no-repeat; background-position: center;height:800px;margin-top:70px;" id='page-top'>
                    <div class="container my-auto text-center ">
                        <h1 class="mb-1"><?php echo $corp ?></h1>
                        <h3 class="mb-5">
                            <em>??zel E??itim Kurumu</em>
                        </h3>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" role="button" href="#<?php echo ($carousel == 'active') ? 'myCarousel' : 'about'; ?>"><i style='font-size:60px;' class="fa fa-angle-down" aria-hidden="true"></i></a>
                        <div class="overlay"></div>
                    </div>
                </header>

                <section class="" style='display:<?php echo ($carousel == 'active') ? '' : 'none;'; ?>'>
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
                            $vahset = '[';
                            for ($i = 0; $i < count($diffar); $i++) {
                                if (($diffar == '.') && ($diffar == '..') && ($diffar == '...')) {
                                } else {
                                    $isac = ($i == 0) ? 'active' : '';
                                    $biga = strtoupper(str_replace(['.jpeg', '.png', '.jpg'], '', $diffar[$i]));
                                    $bb = str_replace(['-', ' ', '"', "`", ",", ":", "!", "^", "+", "/", "#"], '', $biga);
                                    echo '<div class="carousel-item align-center ' . $isac . '" style="position:relative; width:100%; margin:auto">
                                                <img id="' . $bb . '" src="' . $examdir . $diffar[$i] . '" style="height: auto;">
                                                <div class="carousel-caption">
                                                    <h3>' . $biga . '</h3>                                                    
                                                </div>
                                            </div>';
                                    $vahset .= '"' . $bb . '",';
                                }
                            }
                            $vahset .= ']';
                            echo '<script>var vahset = ' . $vahset . ';console.log(vahset);</script>';
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

                <section id="about" class="content-section bg-light">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-lg-10 mx-auto mb-5 py-3 my-5">
                                <h1><?php echo $corp; ?></h1>
                                <p class="lead mb-5">
                                    <span>
                                        ??zel e??itim sekt??r??ndeki yerimizde ????rencilerimize
                                    </span>
                                </p>
                                <a class="btn btn-dark btn-xl js-scroll-trigger" role="button" href="#services">Ne mi Vadediyoruz ?</a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="container-fluid py-0" style='display:none;'>
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

                <section id="services" class="content-section bg-primary text-white text-center">
                    <div class="container" height=600>
                        <div class="content-section-heading p-4">
                            <h3 class="text-light mb-0 my-4"><?php echo $corp; ?></h3>
                            <h2 class="mb-5">E??itimde <b><?php echo $corp; ?></b> Fark??</h2>
                        </div>
                        <div class="row my-4 p-4">
                            <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                                <span class="mx-auto service-icon rounded-circle mb-3" style="font-size:25px;"> <i class="fa fa-angellist" aria-hidden="true"></i> </span>
                                <h4><strong>Ko??luk Sistemi</strong></h4>
                                <p class="mb-0 text-faded">Her ????renciye destek olacak bir e??itim ko??u.</p>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                                <span class="mx-auto service-icon rounded-circle mb-3" style="font-size:25px;"><i class="icon-pencil"></i></span>
                                <h4><strong>??dev Takibi</strong></h4>
                                <p class="mb-0 text-faded">Haftal??k ??dev takibi.</p>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                                <span class="mx-auto service-icon rounded-circle mb-3" style="font-size:25px;"><i class="fas fa-chalkboard-teacher    "></i></span>
                                <h4><strong>S??n??flar</strong></h4>
                                <p class="mb-0 text-faded"><span>??zel ders niteli??i ta????yan s??n??flar.</span></p>
                            </div>
                            <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                                <span class="mx-auto service-icon rounded-circle mb-3" style="font-size:25px;"> <i class="fas fa-book-reader    "></i> </span>
                                <h4><strong>Soru ????z??m??</strong></h4>
                                <p class="mb-0 text-faded">1 e 1 soru ????z??m?? ve takip sistemi.</p>
                            </div>
                        </div>
                        <div class='my-2 mb-0 py-4'>
                            <a class="p-6  btn btn-dark btn-xl js-scroll-trigger" role="button" href="#portfolio">Neden Biz ?</a>
                        </div>
                    </div>
                </section>
                <section id="portfolio" class="content-section">
                    <div class="container">
                        <div class="content-section-heading text-center">
                            <h3 class="text-secondary mb-0"><?php echo $corp; ?></h3>
                            <h2 class="mb-5">Neden Biz?</h2>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <span class="portfolio-item">
                                    <div class="caption">
                                        <div class="caption-content p-2" style='border-radius:15px;background-color: #a7ddfa83; '>
                                            <h2>Neden Biz?</h2>
                                            <p style='color:black;'><?php echo $why1 ?></p>
                                        </div>
                                    </div>
                                    <img class="img-fluid" src="assets/img/portfolio-1.jpg">
                                </span>
                            </div>
                            <div class="col-lg-6">
                                <a class="portfolio-item">
                                    <div class="caption">
                                        <div class="caption-content p-2" style='border-radius:15px;background-color: #a7ddfa83;'>
                                            <h2>Neden Biz?</h2>
                                            <p style='color:black;'><?php echo $why2 ?></p>
                                        </div>
                                    </div>
                                    <img class="img-fluid" src="assets/img/portfolio-2.jpg">
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a class="portfolio-item">
                                    <div class="caption">
                                        <div class="caption-content p-2" style='border-radius:15px;background-color: #a7ddfa83;'>
                                            <h2>Neden Biz?</h2>
                                            <p style='color:black;'><?php echo $why3 ?></p>
                                        </div>
                                    </div>
                                    <img class="img-fluid" src="assets/img/portfolio-3.jpg">
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a class="portfolio-item">
                                    <div class="caption">
                                        <div class="caption-content p-2" style='border-radius:15px;background-color: #a7ddfa83;'>
                                            <h2>Neden Biz?</h2>
                                            <p style='color:black;'><?php echo $why4 ?></p>
                                        </div>
                                    </div>
                                    <img class="img-fluid" src="assets/img/portfolio-4.jpg">
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="maps" class="content-section" style='position:relative;background-color:#9900BE;color:white; background-image: url("assets/img/map.png");background-repeat: inline-repeat;  background-position: center;height:580px;'>
                    <div class="container">
                        <div class="row">
                            <div class="col bg-dark align-item-center my-3">
                                <table class="table table-borderless text-left table-hover table-stripped" style="border-radius:10px;margin-top:center; margin-bottom:center;">
                                    <tbody class="text-left">
                                        <tr>
                                            <td style="color:white;">Adres</td>
                                            <td style="color:white;">:</td>
                                            <td style="color:white;"><?php echo $address; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:white;">Telefon</td>
                                            <td style="color:white;">:</td>
                                            <td style="color:white;"><?php echo $phone; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="color:white;">E-Posta</td>
                                            <td style="color:white;">:</td>
                                            <td style="color:white;"><?php echo $email; ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="col my-3">
                                <iframe src="<?php echo $map ?>" width="auto" height="356" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </section>
                <section id='creator' class="d-flex " style='position:relative;background-color:#9900BE;color:white; background-image: url("https://avatars.githubusercontent.com/u/81029510?s=400&u=76f58bc809770b4309245a0fd3f1b3806d6739a3&v=4");background-repeat: no-repeat;  background-position: left;height:400px;'>
                    <div class="container text-center" style='margin:auto;'>
                        <div class="row">
                            <div class="col-lg-10 mx-auto">
                                <h1>????zelti Software</h1>
                                <p class="lead mb-5">
                                    <span>
                                        Bu Web Sayfas?? ????zelti Software Taraf??ndan geli??tirilmi??tir.
                                    </span>
                                </p>
                                <a href="https://github.com/Muhammedska" class='btn text-light ' height=70><i class="fab fa-github" style='font-size:60px;'></i></a>
                                <a href="https://www.instagram.com/cozeltisoftware/" class='btn text-light ' height=70><i class="fab fa-instagram" style='font-size:60px;'></i></a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <footer class="bg-white sticky-footer bg-dark">
            <div class="container-fluid my-auto bg-dark my-4 p-5" style='color:gray'>

                <div class="text-center my-auto copyright"><span>Copyright ?? Walle 2022 & BY: ????zelti Software</span></div>
            </div>
        </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>

    <script>
        function detectMob() {
            const toMatch = [
                /Android/i,
                /webOS/i,
                /iPhone/i,
                /iPad/i,
                /iPod/i,
                /BlackBerry/i,
                /Windows Phone/i
            ];

            return toMatch.some((toMatchItem) => {
                return navigator.userAgent.match(toMatchItem);
            });
        }


        /* System Runners */

        if (detectMob() == true) {
            document.getElementById('creator').style.color = 'white';
            var i = 0;
            for (m = 0; m < vahset.length; m++) {
                console.log(vahset[m]);
                document.getElementById(vahset[m]).style.width = window.innerWidth + 'px';
            }
        }else{
            var i = 0;
            for (m = 0; m < vahset.length; m++) {
                console.log(vahset[m]);
                document.getElementById(vahset[m]).style.height = '600px';
            }
        }
        <?php if ($notshow == 'active' and count($notify) != 0) {
            echo '$(document).ready(function() {' . '$("#notify").modal("show");' . ' });';
        } ?>
    </script>
</body>


</html>