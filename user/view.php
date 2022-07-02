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
$tarih = date("Y-m-d h:i:sa");
$vname = $_GET['file'];
$wagent = new DbConnecter($_GET['dir'].'info.db');
$sql = "INSERT INTO {$_GET['name']} (videoname, watcher, date) VALUES ('{$vname}','{$ID}','{$tarih}');";
$wagent->exec($sql);


$sql = "SELECT * FROM app WHERE var='name'";
$results = $agent->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$corp = $row[1];
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
        html{
            user-select: none;
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
                            <?php echo $corp?>
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

                    <div>
                        <h3 class="text-dark mb-4"><?php echo strtoupper($_GET['name']) . '&nbsp;<i class="fas fa-angle-right"></i>&nbsp;<a href="./video.php?examname='.$_GET["examname"].'">' . $_GET["examname"].'</a>'; ?></h3>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Video</h6>
                                </div>

                                <div class="card-body">
                                    <video controls style="width:100%;" src="<?php echo $_GET['dir'] . $_GET['name'] . '/' . $_GET['file'] ?>"></video>
                                </div>
                                <div class="card-footer p-2">
                                    
                                    <a href="<?php echo $_GET['dir'] . $_GET['name'] . '/' . $_GET['file'] ?>" class="btn btn-primary" download><i class="fa fa-download" aria-hidden="true"></i>&nbsp;&nbsp;Videoyu İndir</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 font-weight-bold"><?php echo strtoupper($_GET['name'])?></p>
                                </div>
                                <div class="card-body container p-2">
                                    <?PHP
                                    $dirname = $_GET['dir'];
                                    $lection = $_GET['name'];
                                    $examname = $_GET['examname'];
                                    $finder = scandir($dirname . "{$lection}");
                                    array_diff($finder, [".", ".."]);
                                    sort($finder);
                                    for ($i = 0; $i < count($finder); $i++) {
                                        $checkdir = $dirname . "/{$lection}" . "/" . $finder[$i];
                                        if (is_file($checkdir)) {
                                            $link = str_replace('.mp4', "", $finder[$i]);
                                            echo "<a class='alert btn-primary btn text-left' style='width:100%; position:relative;' href='./view.php?file={$finder[$i]}&name={$lection}&dir={$dirname}&examname={$examname}'><i class='fa fa-arrow-right'></i>&nbsp; {$link} &nbsp;</a>";
                                        }
                                    }
                                    ?>
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