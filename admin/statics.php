<?PHP
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'kurum') {
    } else {
        echo "<script>window.location.href='../index.php'</script>";
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
$datax = [];
$IDS = '[';
$NS = '[';
while ($row = $results->fetchArray()) {
    $datax += [$row['ID'] => array($row['ID'], $row['name'], $row['grade'], $row['pp'])];
    array_push($datar, array($row['ID'], $row['name'], $row['grade'], $row['pp']));
    //$datar += [];
    $IDS .= '"' . $row['ID'] . '",';
    $NS .= '"' . strtolower($row['name']) . '",';
};

$sql = "SELECT * FROM teacher ORDER BY ID";
$results = $db->query($sql);
$datat = [];
$datam = [];
while ($row = $results->fetchArray()) {
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'], $row['pp'])];
    array_push($datam, array($row['ID'], $row['name'], $row['lesson'], $row['pp']));
};

$IDS .= ']';
$NS .= ']';
echo "<script>const IDS = $IDS; const NS = $NS;</script>";

$examdir = "../src/video/exams/";
$folders = scandir($examdir);
sort($folders);
array_reverse($folders);
array_diff($folders, [".", ".."]);
$exlist = [];

if (empty($_GET['type'])) {
    $_GET['type'] = 'student';
}
if (empty($_GET['q'])) {
    $q = 'null';
} else {
    $q = $_GET['q'];
}
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
                    <li class="nav-item"><a class="nav-link" href="./adds.php"><i class="fas fa-user-plus"></i><span>Öğrenci Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./addt.php"><i class="fas fa-user-plus"></i><span>Öğretmen Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./exams.php"><i class="fas fa-edit"></i><span>Sınavlar</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="./statics.php"><i class="fas fa-chart-line"></i><span>İstatistikler</span></a></li>
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item"><a class="nav-link" href="./table.php?list=student"><i class="fas fa-table"></i><span>Öğrenci</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./table.php?list=teacher"><i class="fas fa-table"></i><span>Öğretmen</span></a></li>

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
                            WALLE
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
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Çıkış</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="container-fluid text-left">
                    <?php
                    if (!empty($_GET["ret"])) {
                        if ($_GET['ret'] == "true") {
                            if ($_GET['reqtype'] == 'adds') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni öğrenci başarıyla eklendi. 👍</div>";
                            } else if ($_GET['reqtype'] == 'del') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Öğrenci başarıyla silindi. 👍</div>";
                            }
                        } else {
                            if ($_GET['reqtype'] == 'adds') {
                                echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni öğrenci eklenemedi. 🤔</div>";
                            } else if ($_GET['reqtype'] == 'del') {
                                echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Öğrenci silinemedi. 🤔</div>";
                            }
                        }
                    }
                    ?>

                    <h3 class="text-dark mb-4">İstatistikler</h3>

                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card mb-3 shadow">
                                <div class="card-header">
                                    <a href='./statics.php?type=student' class="btn btn-large btn<?php echo ($_GET['type'] == 'student') ? '' : '-outline'; ?>-primary">
                                        Öğrenci <span class="badge badge-<?php echo ($_GET['type'] == 'student') ? 'dark' : 'primary'; ?>"><?php echo count($datar); ?></span>
                                    </a>
                                    <a href='./statics.php?type=teacher' class="btn btn-large btn<?php echo ($_GET['type'] == 'teacher') ? '' : '-outline'; ?>-primary">
                                        Öğretmen <span class="badge badge-<?php echo ($_GET['type'] == 'teacher') ? 'dark' : 'primary'; ?>"><?php echo count($datam); ?></span>
                                    </a>
                                    <a href='./statics.php?type=exams' class="btn btn-large btn<?php echo ($_GET['type'] == 'exams') ? '' : '-outline'; ?>-primary">
                                        Sınav <span class="badge badge-<?php echo ($_GET['type'] == 'exams') ? 'dark' : 'primary'; ?>"><?php echo count(array_diff($folders, ['.', '..'])); ?></span>
                                    </a>

                                </div>
                                <div class="card-body">
                                    <?php
                                    if ($_GET['type'] == 'student') {
                                        for ($i = 0; $i < count($datar); $i++) {
                                            if ($datar[$i][3] == 'peter') {
                                                $pp = "../assets/img/dogs/image2.jpeg";
                                            } else if ($datar[$i][3] == 'franko') {
                                                $pp = "../assets/img/dogs/image3.jpeg";
                                            } else if ($datar[$i][3] == 'ralph') {
                                                $pp = "../assets/img/dogs/image4.jpeg";
                                            } else if ($datar[$i][3] == 'jessi') {
                                                $pp = "../assets/img/dogs/image5.jpeg";
                                            } else if ($datar[$i][3] == 'leo') {
                                                $pp = "../assets/img/dogs/image6.jpeg";
                                            } else if ($datar[$i][3] == 'mike') {
                                                $pp = "../assets/img/dogs/image7.jpeg";
                                            }

                                            $upp = strtoupper($datar[$i][1]);
                                            echo "<a class='media btn btn-primary my-3'  href='./statics.php?type=student&q={$datar[$i][0]}'>";
                                            echo '<img style="margin-top:auto;margin-bottom:auto;" class="mr-3 rounded-circle" width="60" height="60" src=' . $pp . '>';
                                            echo "<h4 style='margin-top:auto;margin-bottom:auto;'>{$upp}</h4>";
                                            echo "</a>";
                                        }
                                    } else if ($_GET['type'] == 'teacher') {
                                        for ($i = 0; $i < count($datam); $i++) {
                                            if ($datam[$i][3] == 'peter') {
                                                $pp = "../assets/img/dogs/image2.jpeg";
                                            } else if ($datam[$i][3] == 'franko') {
                                                $pp = "../assets/img/dogs/image3.jpeg";
                                            } else if ($datam[$i][3] == 'ralph') {
                                                $pp = "../assets/img/dogs/image4.jpeg";
                                            } else if ($datam[$i][3] == 'jessi') {
                                                $pp = "../assets/img/dogs/image5.jpeg";
                                            } else if ($datam[$i][3] == 'leo') {
                                                $pp = "../assets/img/dogs/image6.jpeg";
                                            } else if ($datam[$i][3] == 'mike') {
                                                $pp = "../assets/img/dogs/image7.jpeg";
                                            }

                                            $upp = strtoupper($datam[$i][1]);
                                            echo "<a class='media text-left btn btn-primary my-3' href='./statics.php?type=teacher&q={$datam[$i][0]}'>";
                                            echo '<img style="margin-top:auto;margin-bottom:auto;max-height:60px;max-widtht:60px;min-width:60px;" class="mr-3 rounded-circle" width="60" height="60" src=' . $pp . '>';
                                            echo "<h4 style='margin-top:auto;margin-bottom:auto;'>{$upp}</h4>";
                                            //echo "<p class='media-body'>{$datam[$i][2]}</p>";
                                            echo "</a>";
                                        }
                                    } else if ($_GET['type'] == 'exams') {
                                        for ($i = 0; $i < count($folders); $i++) {
                                            if (!($folders[$i] == '.') && !($folders[$i] == '..') && !($folders[$i] == "active.txt")) {
                                                $upp = strtoupper($folders[$i]);
                                                echo "<a class='media text-left btn btn-primary my-3' href='./statics.php?type=exams&q=" . $folders[$i] . "'>";
                                                echo '<i style="margin-top:auto;margin-bottom:auto;max-height:60px;max-widtht:60px;min-width:60px;" class="my-2 rounded-circle fas fa-file-alt fa-2x text-gray-300" width="60" height="60"></i>';
                                                echo "<h4 style='margin-top:auto;margin-bottom:auto;'>{$upp}</h4>";
                                                //echo "<p class='media-body'>{$datam[$i][2]}</p>";
                                                echo "</a>";
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-3 shadow" style="<?php echo ($_GET['type'] == 'null') ? 'display:none;' : ''; ?>">
                                <div class="card-header">
                                    <i class="fas fa-chart-area mr-1"></i>
                                    Grafik
                                </div>
                                <div class="card-body text-center">
                                    <?php
                                    if ($q == 'null') {
                                        echo "<div class='mb-4 my-4 p-4'><i class='fa fa-frog' style='font-size:80px;'></i><br>Listeleme için bir kişi veya sınav seçiniz.</div>";
                                    } else {
                                        if ($_GET['type'] == 'student') {
                                            echo '<div class="d-flex">
                                                    <canvas id="lessonGraph" width=300></canvas>
                                                </div>
                                                <div class="text-center small mt-4">
                                                    <span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Matematik</span>
                                                    <span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Türkçe</span>
                                                    <span class="mr-2"><i class="fas fa-circle text-info"></i>&nbsp;Geometri</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#BDE4A7;"></i>&nbsp;Kimya</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#7A9CC6;"></i>&nbsp;Fizik</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#9B2226;"></i>&nbsp;Biyoloji</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#BB3E03;"></i>&nbsp;Tarih</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#005F73;"></i>&nbsp;Coğrafya</span>
                                                </div>';
                                        } else if ($_GET['type'] == 'teacher') {
                                            echo '<div class="d-flex">
                                                    <canvas id="teacherstat" width=300></canvas>
                                                </div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="card mb-3 shadow">
                                <div class="card-header">
                                    <i class="fas fa-scroll mr-1"></i>
                                    Logs
                                </div>
                                <div class="card-body text-center">
                                    <div class="container">
                                        <input type="text" class="form-control" id="filters">
                                    </div>
                                    <div class='mb-4 my-4 p-4' id='undefineds' style="display:none;">
                                        <i class='fa fa-frog' style='font-size:80px;'></i><br>Filtrelenen içerik bulunamadı.

                                    </div>

                                    <?php
                                    if ($q == 'null') {
                                        echo "<div class='mb-4 my-4 p-4'><i class='fa fa-frog' style='font-size:80px;'></i><br>Kayıt Günlükleri için bir kişi veya sınav seçiniz.</div>";
                                    } else {
                                        if ($_GET['type'] == 'student') {
                                            $sql = "SELECT * FROM log WHERE user='{$datax[$q][1]}'";
                                            $results = $db->query($sql);
                                            //$results = $res->execute();
                                            $notify = [];
                                            while ($row = $results->fetchArray()) {
                                                array_push($notify, array($row['mission'], $row['logtm'], $row['user']));
                                            };
                                            $con = 1;
                                            if (count($notify) == 0) {
                                                echo "<div class='mb-4 my-4 p-4'><i class='fa fa-frown' style='font-size:80px;'></i><br>{$datax[$q][1]} için herhangi bir kayıt bulunamadı.</div>";
                                            } else {
                                                foreach ($notify as $key => $value) {
                                                    echo "<div class='media text-muted pt-3' id='dataTable'>";
                                                    echo "<div class='media-body pb-3 mb-0 small lh-125 border-bottom border-gray'>";
                                                    echo "<div class='d-flex justify-content-between align-items-center w-100'>";
                                                    echo "<strong class='text-gray-dark'>{$value[1]} </strong>";
                                                    echo "</div>";
                                                    echo "<div class='d-flex justify-content-between align-items-center w-100'>";
                                                    echo "<strong class='text-gray-dark'>{$value[0]}</strong>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    $con++;
                                                }
                                            }
                                        } elseif ($_GET['type'] == 'teacher') {
                                            $sql = "SELECT * FROM log WHERE user='{$datat[$q][1]}'";
                                            $results = $db->query($sql);
                                            //$results = $res->execute();
                                            $notify = [];
                                            while ($row = $results->fetchArray()) {
                                                array_push($notify, array($row['mission'], $row['logtm'], $row['user']));
                                            };
                                            $con = 1;
                                            array_reverse($notify);
                                            if (count($notify) == 0) {
                                                echo "<div class='mb-4 my-4 p-4'><i class='fa fa-frown' style='font-size:80px;'></i><br>{$datat[$q][1]} için herhangi bir kayıt bulunamadı.</div>";
                                            } else {
                                                echo '<ul class="list-group list-group-flush text-left" id="dataTable">';
                                                foreach (array_reverse($notify) as $key => $value) {
                                                    echo '<li class="list-group-item">';
                                                    echo '<div class="row align-items-center no-gutters">';
                                                    echo '<div class="col mr-2">';
                                                    echo '<h6 class="mb-0 text-dark"><span class="badge badge-primary">' . $con . '</span>&nbsp;&nbsp;<strong>' . $value[0] . '</strong></h6><span class="text-xs">' . $value[1] . '</span>';
                                                    echo '</div>';
                                                    echo '</div></li>';
                                                    $con++;
                                                }
                                                echo '</ul>';
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
        </div>
    </div>
    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
            <div class="text-center my-auto copyright"><span>Copyright © Walle 2022</span></div>
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
        $(document).ready(function() {
            $("#filters").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#dataTable div").filter(function() {
                    if (!$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)) {
                        $('#undefineds').show();
                    } else if ($(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)) {
                        $('#undefineds').hide();
                    }
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#pwd").on("keyup", function() {
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
            $("#uname").on("keyup", function() {
                var valuey = $(this).val().toLowerCase();
                if (NS.indexOf(valuey) != -1) {
                    console.log('found');
                    document.getElementById("crashn").style.display = "";
                    document.getElementById("addstd").disabled = true;
                } else {
                    document.getElementById("crashn").style.display = "none";
                    document.getElementById("addstd").disabled = false;
                }
            });
        });
        console.log(IDS, NS)

        function checkval() {
            var value = document.getElementById("pwd").value;
            if (IDS.indexOf(value) != -1) {
                document.getElementById("crash").style.display = "";
                document.getElementById("addstd").disabled = true;
            } else {
                document.getElementById("crash").style.display = "none";
                document.getElementById("addstd").disabled = false;
            }
        }
    </script>
    <script>
        <?php
        if ($_GET['type'] == 'student' && $q != 'null') {
            $lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
            $lesanalysis = "const lesanly = [";
           
            $asql = "SELECT * FROM statsstudent WHERE ID='{$q}'";
            $aresults = $db->query($asql);
            $uniqlist = [];

            while ($row = $aresults->fetchArray()) {
                array_push($uniqlist, $row['subject']);
            }
            $detialVal = [];
            for ($i = 0; $i < count($lessons); $i++) {
                $indix = 0;
                for ($n = 0; $n < count($uniqlist); $n++) {
                    if ($lessons[$i] == $uniqlist[$n]) {
                        $indix++;
                    }
                }
                array_push($detialVal, $indix);
            }
            for ($i = 0; $i < count($detialVal); $i++) {
                if ($i + 1 == count($detialVal)) {
                    $lesanalysis .= $detialVal[$i] . "]";
                } else {
                    $lesanalysis .= $detialVal[$i] . ",";
                }
            }
            echo $lesanalysis . ";";
        } ?>
        var ctx = document.getElementById("lessonGraph");
        let lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
        var mychart2 = new Chart(ctx, {
            "type": "doughnut",
            "data": {
                "labels": lessons,
                "datasets": [{
                    "label": "",
                    "backgroundColor": ["#4e73df", "#1cc88a", "#36b9cc", "#BDE4A7", "#7A9CC6", "#9B2226", "#BB3E03", "#005F73"],
                    "borderColor": ["#ffffff", "#ffffff", "#ffffff"],
                    "data": lesanly
                }]
            },
            "options": {
                "maintainAspectRatio": false,
                "legend": {
                    "display": false
                },
                "title": {}
            }
        })
    </script>
    <script>
        <?php
        if ($_GET['type'] == 'teacher' && $q != 'null') {
            $months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $teacherw = "const teacherw = [";
            $asql = "SELECT * FROM statsteacher WHERE teacher = '{$q}';";
            $aresults = $db->query($asql);
            $uniqteacher = [];

            while ($row = $aresults->fetchArray()) {
                array_push($uniqteacher, $row['month']);
            }
            $detialVal = [];
            for ($i = 0; $i < count($months); $i++) {
                $indix = 0;
                for ($n = 0; $n < count($uniqteacher); $n++) {
                    if ($months[$i] == $uniqteacher[$n]) {
                        $indix++;
                    }
                }
                array_push($detialVal, $indix);
            }
            for ($i = 0; $i < count($detialVal); $i++) {
                if ($i + 1 == count($detialVal)) {
                    $teacherw .= $detialVal[$i] . "]";
                } else {
                    $teacherw .= $detialVal[$i] . ",";
                }
            }
            echo $teacherw . ";";
        }
        ?>
        var ctx = document.getElementById("teacherstat");
        var config = new Chart(ctx, {
            "type": 'line',
            "data": {
                "labels": ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"],
                "datasets": [{
                    "label": "Öğretmen soru Çözüm sayısı",
                    "backgroundColor": ["#4e73df", "#1cc88a", "#36b9cc", "#BDE4A7", "#7A9CC6", "#9B2226", "#BB3E03", "#005F73", '#0095a6', '#1b2252', '#25521b', '#826c1a'],
                    "borderColor": ["#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000"],
                    "data": teacherw
                }]
            },
            "options": {
                "maintainAspectRatio": true,
                "legend": {
                    "display": false
                },
                "title": {}
            },
            //"options": {
            //    "responsive": true,
            //    "plugins": {
            //        "legend": {
            //            "position": 'top',
            //        },
            //        "title": {
            //            "display": true,
            //            "text": 'Chart.js Line Chart'
            //        }
            //    }
            //},
        })
    </script>
</body>

</html>