<?php
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'student') {
    }
} else {
    echo "<script>window.location.href = '../index.php'</script>";
}
class DbConnecter extends SQLite3
{
    function __construct($path)
    {
        $this->open($path);
    }
}
$ID = $_SESSION['userid'];
$agent = new DbConnecter('../src/database/users.db');
$lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
$sql = "SELECT * FROM teacherreq WHERE stid = '{$ID}';";
$results = $agent->prepare($sql);
$res = $results->execute();
//$row = $res->fetchArray(SQLITE3_NUM);
//var_dump($row);
$waitlist = [];
while ($row = $res->fetchArray()) {
    //var_dump($row);    
    array_push($waitlist, $row[2]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Walle</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="shortcut icon" href="../favicon.svg" type="image/x-icon">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav id='mnav' class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><img width='39px' src="../src/img/favicon.svg" alt="" srcset=""></div>
                    <div class="sidebar-brand-text mx-3"><span>Walle</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link active" href="../user/video.php"> <i class="fas fa-film"></i> <span>Video Çözüm</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="../user/user.php"><i class="fas fa-user"></i><span>Profil</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fa fa-arrow-circle-left"></i><span>Çıkış</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline">
                    <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>

                </div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid">
                        <button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <div class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            WALLE
                        </div>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">

                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow">
                                    <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">
                                        <span class=" d-lg-inline mr-2 text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
                                        <img class="border rounded-circle img-profile " src=<?php echo $_SESSION['pp']; ?>>
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-right animated--grow-in">
                                        <a class="dropdown-item" href="../logout.php">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Çıkış</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET["ret"])) {
                        if ($_GET['ret'] == "true") {
                            if ($_GET['reqtype'] == 'changepp') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Profil resminiz başarıyle değiştirildi. 👍</div>";
                            } else if ($_GET['reqtype'] == 'lesson') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Soru çözüm randevu talebiniz başarıyla alınmıştır. 👍</div>";
                            }
                        }
                    }
                    ?>

                    <h3 class="text-dark mb-4">Video Çözümler</h3>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Denemeler</h6>
                                </div>

                                <div class="card-body" style="max-height:400px;overflow-y:auto;">
                                    <?php
                                    $examdir = "../src/video/exams/";
                                    $folders = scandir($examdir);
                                    sort($folders);
                                    array_reverse($folders);
                                    array_diff($folders, [".", ".."]);
                                    $firstexam = "";
                                    $istakefe = false;
                                    $isopened = [];
                                    for ($i = 0; $i < count($folders); $i++) {
                                        if (!($folders[$i] == '.') && !($folders[$i] == '..' && !($folders[$i] == "active.txt"))) {
                                            if (is_file($examdir . $folders[$i] . "/active.txt")) {
                                                $activedir = fgets(fopen($examdir . $folders[$i] . "/active.txt", "r"));
                                                if ($activedir == "1") {
                                                    echo "<a class='btn btn-primary container text-center mb-3' href='./video.php?examname={$folders[$i]}'>{$folders[$i]}</a>";
                                                    if ($istakefe == false) {
                                                        $firstexam = $folders[$i];
                                                        $istakefe = true;
                                                    }
                                                    array_push($isopened, $folders[$i]);
                                                }
                                            } else {
                                                $secfile = fopen($examdir . $folders[$i] . "/active.txt", "w");
                                                fwrite($secfile, "0");
                                                fclose($secfile);
                                            }
                                        }
                                    }

                                    if (empty($_GET['examname'])) {
                                        $dirname = $examdir . $firstexam . "/";
                                        $examname = $firstexam;
                                    } else {
                                        $dirname = $examdir . $_GET['examname'] . "/";
                                        $examname = $_GET['examname'];
                                    }

                                    ?>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Hızlı Ulaşım</h6>
                                </div>

                                <div class="card-body">
                                    <div class="container flex-wrap text-center">
                                        <a href="#matematik" class="btn mb-3 btn-primary" style="width:120px">Matematik</a>
                                        <a href="#fizik" class="btn mb-3 btn-primary" style="width:120px">Fizik</a>
                                        <a href="#kimya" class="btn mb-3 btn-primary" style="width:120px">Kimya</a>
                                        <a href="#biyoloji" class="btn mb-3 btn-primary" style="width:120px">Biyoloji</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">

                            <div class="row">
                                <div class="col">
                                    <div class="card shadow mb-3">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">
                                                Deneme -
                                                <?PHP
                                                echo $examname;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card shadow mb-3" id="matematik">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Matematik</p>
                                        </div>
                                        <div class="card-body container p-2">
                                            <?PHP
                                            if (count($isopened) != 0) {
                                                $finder = scandir($dirname . "matematik");
                                                array_diff($finder, [".", ".."]);
                                                for ($i = 0; $i < count($finder); $i++) {
                                                    $checkdir = $dirname . "/matematik" . "/" . $finder[$i];
                                                    if (is_file($checkdir)) {
                                                        $link = str_replace('.jpeg', "", $finder[$i]);
                                                        echo "<a class='alert btn-primary btn text-left ' style='width:100%; position:relative;' href='./view.php?file={$finder[$i]}&name=matematik&dir={$dirname}&examname={$examname}'><i class='fa fa-arrow-right'></i>&nbsp; {$link} &nbsp;</a>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card shadow mb-3" id="fizik">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Fizik</p>
                                        </div>
                                        <div class="card-body container p-2">
                                            <?PHP
                                            if (count($isopened) != 0) {
                                                $finder = scandir($dirname . "fizik");
                                                array_diff($finder, [".", ".."]);
                                                for ($i = 0; $i < count($finder); $i++) {
                                                    $checkdir = $dirname . "/fizik" . "/" . $finder[$i];
                                                    if (is_file($checkdir)) {
                                                        $link = str_replace('.jpeg', "", $finder[$i]);
                                                        echo "<a class='alert btn-primary btn text-left' style='width:100%; position:relative;' href='./view.php?file={$finder[$i]}&name=fizik&dir={$dirname}&examname={$examname}'><i class='fa fa-arrow-right'></i>&nbsp; {$link} &nbsp;</a>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card shadow mb-3" id="kimya">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Kimya</p>
                                        </div>
                                        <div class="card-body container p-2">
                                            <?PHP
                                            if (count($isopened) != 0) {
                                                $finder = scandir($dirname . "kimya");
                                                array_diff($finder, [".", ".."]);
                                                for ($i = 0; $i < count($finder); $i++) {
                                                    $checkdir = $dirname . "/kimya" . "/" . $finder[$i];
                                                    if (is_file($checkdir)) {
                                                        $link = str_replace('.jpeg', "", $finder[$i]);
                                                        echo "<a class='alert btn-primary btn text-left' style='width:100%; position:relative;' href='./view.php?file={$finder[$i]}&name=kimya&dir={$dirname}&examname={$examname}'><i class='fa fa-arrow-right'></i>&nbsp; {$link} &nbsp;</a>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card shadow" id="biyoloji">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Biyoloji</p>
                                        </div>
                                        <div class="card-body container p-2">
                                            <?PHP
                                            if (count($isopened) != 0) {
                                                $finder = scandir($dirname . "biyoloji");
                                                array_diff($finder, [".", ".."]);
                                                for ($i = 0; $i < count($finder); $i++) {
                                                    $checkdir = $dirname . "/biyoloji" . "/" . $finder[$i];
                                                    if (is_file($checkdir)) {
                                                        $link = str_replace('.jpeg', "", $finder[$i]);
                                                        echo "<a class='alert btn-primary btn text-left' style='width:100%; position:relative;' href='./view.php?file={$finder[$i]}&name=biyoloji&dir={$dirname}&examname={$examname}'><i class='fa fa-arrow-right'></i>&nbsp; {$link} &nbsp;</a>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-5 d-none">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold">Forum Settings</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form>
                                        <div class="form-group"><label for="signature"><strong>Signature</strong><br></label><textarea class="form-control" rows="4" name="signature"></textarea></div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch"><input class="custom-control-input" type="checkbox" id="formCheck-1"><label class="custom-control-label" for="formCheck-1"><strong>Notify me about new replies</strong></label></div>
                                        </div>
                                        <div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Save Settings</button></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Walle 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="../assets/js/chart.min.js"></script>

    <script src="../src/js/winset.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>