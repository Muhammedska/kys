<?PHP
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'student') {
        echo "<script>window.location.href = '../user/user.php'</script>";
    }elseif ($_SESSION['type'] == 'teacher') {
        echo "<script>window.location.href = '../teacher/user.php'</script>";
    } elseif ($_SESSION['type'] == 'kurum') {
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

$db = new DbConnecter('../src/database/users.db');
$sql = "SELECT * FROM student ORDER BY ID";
$results = $db->query($sql);
$datar = [];
while ($row = $results->fetchArray()) {
    $datar += [$row['ID'] => array($row['ID'], $row['name'], $row['grade'])];
};

$sql = "SELECT * FROM teacher ORDER BY ID";
$results = $db->query($sql);
$datat = [];
while ($row = $results->fetchArray()) {
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'], $row['pp'])];
};

$examdir = "../src/video/exams/";
$folders = scandir($examdir);
sort($folders);
array_reverse($folders);
$folders = array_diff($folders, [".", ".."]);

$sql = "SELECT * FROM app WHERE var='name'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$corp = $row[1];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="shortcut icon" href="../favicon.svg" type="image/x-icon">
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
                    <li class="nav-item"><a class="nav-link" href="./admin.php"><i class="fas fa-home"></i><span>Ana Sayfa</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./adds.php"><i class="fas fa-user-plus"></i><span>????renci Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./addt.php"><i class="fas fa-user-plus"></i><span>????retmen Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="./exams.php"><i class="fas fa-edit"></i><span>S??navlar</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./statics.php"><i class="fas fa-chart-line"></i><span>??statistikler</span></a></li>
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item"><a class="nav-link" href="./table.php?list=student"><i class="fas fa-table"></i><span>????renci</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./table.php?list=teacher"><i class="fas fa-table"></i><span>????retmen</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
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
                                        <span class=" d-lg-inline mr-2 text-gray-600 small">Kurum</span>
                                        <img class="border rounded-circle img-profile " src=<?php echo $_SESSION['pp']; ?>>
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-right animated--grow-in">
                                        <a class="dropdown-item" href="../logout.php">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;????k????</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid">
                    <div class="container-fluid text-left mb-4">
                        <?php
                        if (!empty($_GET["ret"])) {
                            if ($_GET['ret'] == "true") {
                                if ($_GET['reqtype'] == 'addnotify') {
                                    echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni Bildirim ba??ar??yla eklendi. ????</div>";
                                } else if ($_GET['reqtype'] == 'delreq') {
                                    echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Bildirim ba??ar??yla silindi. ????</div>";
                                }
                            } else {
                                if ($_GET['reqtype'] == 'addt') {
                                    echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni ????retmen eklenemedi. ????</div>";
                                } else if ($_GET['reqtype'] == 'del') {
                                    echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> ????retmen silinemedi. ????</div>";
                                }
                            }
                        }
                        ?>
                        <h3 class="text-dark mb-0">S??navlar</h3>
                    </div>

                    <div class="row">
                        <div class="col-lg-5 col-xl-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">S??nav Olu??tur</h6>
                                </div>
                                <div class="card-body">
                                    <form action="./gear.php" method="get">
                                        <label for="examname">S??nav??n ??smini Giriniz :</label>
                                        <input name="examname" id="examname" class="form-control mb-3" placeholder="S??nav ismini giriniz" required></input>
                                        <input type="text" name="reqtype" value="video" style="display:none;">
                                        <input type="text" name="type" value="create" style="display:none;">
                                        <div id="crash" style="display:none;">Bu s??nav daha ??ncesinde eklenmi??.</div>
                                        <button class="btn btn-primary" type="submit" id='addstd'>S??nav Olu??tur</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-xl-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">S??navlar <span class="badge badge-primary"></span></h6>
                                </div>
                                <div class="card-body" style="max-height:400px;overflow-x:scroll;">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>S??nav ADI</th>
                                                <th>????lem</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $examdir = "../src/video/exams/";
                                            $folders = scandir($examdir);
                                            sort($folders);
                                            array_reverse($folders);
                                            array_diff($folders, [".", ".."]);
                                            $firstexam = "";
                                            $istakefe = false;
                                            $listex = '[';
                                            for ($i = 0; $i < count($folders); $i++) {
                                                
                                                if (!($folders[$i] == '.') && !($folders[$i] == '..' && !($folders[$i] == "active.txt"))) {
                                                    $listex .= '"' . $folders[$i] . '",';
                                                    if (is_file($examdir . $folders[$i] . "/active.txt")) {
                                                        $activedir = fgets(fopen($examdir . $folders[$i] . "/active.txt", "r"));
                                                        $path = $examdir . $folders[$i] . "/active.txt";
                                                        if ($activedir == "1") {
                                                            echo "<tr>";
                                                            echo "<td scope='row'>" . $folders[$i] . "</td>";
                                                            echo "<td>
                                                            <a href='./gear.php?reqtype=video&type=perm&perm=0&path={$path}' class='btn btn-success'><i class='fas fa-lock-open' style='font-size:20px    ;'></i></a>&nbsp;
                                                            <a href='./statics.php?q={$folders[$i]}&type=exams' class='btn btn-success'><i class='fas fa-chart-line' style='font-size:20px    ;'></i></a>
                                                            <a href='./gear.php?reqtype=video&type=delete&path={$path}' class='btn btn-warning' style='display:none;'><i class='fas fa-trash' style='font-size:20px    ;'></i></a>&nbsp;
                                                            </td>";

                                                            if ($istakefe == false) {
                                                                $firstexam = $folders[$i];
                                                                $istakefe = true;
                                                            }
                                                        } else if ($activedir == "0") {
                                                            echo "<tr>";
                                                            echo "<td scope='row'>" . $folders[$i] . "</td>";
                                                            echo "<td>
                                                            <a href='./gear.php?reqtype=video&type=perm&perm=1&path={$path}' class='btn btn-danger'><i class='fas fa-lock' style='font-size:20px  ;'></i></a>&nbsp;
                                                            <a href='./statics.php?q={$folders[$i]}&type=exams' class='btn btn-success'><i class='fas fa-chart-line' style='font-size:20px    ;'></i></a>
                                                            <a href='./gear.php?reqtype=video&type=delete&path={$path}' class='btn btn-warning'  style='display:none;'><i class='fas fa-trash' style='font-size:20px    ;'></i></a>&nbsp;
                                                            </td>";

                                                            if ($istakefe == false) {
                                                                $firstexam = $folders[$i];
                                                                $istakefe = true;
                                                            }
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
                                            $listex .= '""]';
                                            ?>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright ?? Walle 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="../src/js/winset.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/chart.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script>
        <?php echo "const IDS = ".$listex.";\n"?>
        $(document).ready(function() {
         
            $("#examname").on("keyup", function() {
                var valuex = $(this).val().toLowerCase();                
                if (IDS.indexOf(valuex) != -1) {
                    console.log('found');
                    document.getElementById("crash").style.display = "";
                    document.getElementById("addstd").disabled = true;
                } else {
                    document.getElementById("crash").style.display = "none";
                    document.getElementById("addstd").disabled = false;
                }                
            });

        });
    </script>
</body>

</html>