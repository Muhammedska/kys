<?php
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'student') {
    } elseif ($_SESSION['type'] == 'teacher') {
        echo "<script>window.location.href = '../teacher/user.php'</script>";
    } elseif ($_SESSION['type'] == 'kurum') {
        echo "<script>window.location.href = '../admin/admin.php'</script>";
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
$db = new DbConnecter('../src/database/users.db');
$sql = "SELECT * FROM teacher ORDER BY ID";
$results = $db->query($sql);
$datat = [];
while ($row = $results->fetchArray()) {
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'], $row['pp'])];
};

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
                    <li class="nav-item"><a class="nav-link" href="../user/video.php"> <i class="fas fa-film"></i> <span>Video ????z??m</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="../user/user.php"><i class="fas fa-user"></i><span>Profil</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fa fa-arrow-circle-left"></i><span>????k????</span></a></li>
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
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;????k????</a>
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
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Profil resminiz ba??ar??yle de??i??tirildi. ????</div>";
                            } else if ($_GET['reqtype'] == 'lesson') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Soru ????z??m randevu talebiniz ba??ar??yla al??nm????t??r. ????</div>";
                            }
                        }
                    }
                    ?>

                    <h3 class="text-dark mb-4">Profil</h3>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-body text-center shadow">
                                    <img class="rounded-circle mb-3 mt-4" src=<?php echo $_SESSION['pp'] ?> width="160" height="160">
                                    <div class="mb-3"><?php echo $_SESSION['username']; ?></div>
                                    <div class="mb-3">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modelId">
                                            Resmi De??i??tir
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Profil Resimleri</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="d-flex flex-wrap">
                                                                <div class=" align-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image2.jpeg" alt="peter" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=peter&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;
                                                                <div class="text-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image3.jpeg" alt="franko" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=franko&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;
                                                                <div class=" text-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image4.jpeg" alt="ralph" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=ralph&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;
                                                                <div class=" align-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image5.jpeg" alt="jessi" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=jessi&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;
                                                                <div class="text-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image6.jpeg" alt="leo" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=leo&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                                &nbsp;&nbsp;
                                                                <div class=" text-center card-body shadow mb-3 p-2">
                                                                    <img class="rounded-circle mb-3 mt-4 shadow" src="../assets/img/dogs/image7.jpeg" alt="mike" width="160" height="160">
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary btn-sm" onclick="window.location.href='./gear.php?imname=mike&reqtype=changepp'"> Ayarla</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <?php
                                $sql = "SELECT * FROM notify ORDER BY notify";
                                $results = $db->query($sql);
                                $notify = [];
                                while ($row = $results->fetchArray()) {
                                    if ($row['mass'] == 'student') {

                                        array_push($notify, array($row['mass'], $row['notify'], $row['sender']));
                                    }
                                };
                                ?>
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Bildirimler <span class="badge badge-primary"><?php echo (count($notify) == 0) ? 0 : count($notify); ?></span></h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (count($notify) == 0) {
                                        echo '<div class="text-center">
                                            <h4 class="text-muted">Bildirim Yok</h4>
                                        </div>';
                                    } else {
                                        foreach ($notify as $key => $value) {
                                            if ($value[2] == "kurum" && $value[0] == "teacher") {
                                                echo "<div class='media'>
                                            <span class='align-self-center dropdown no-arrow ' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                <a class='btn btn-link btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false' type='button'>
                                                    <img src='../assets/img/dogs/image8.jpeg' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                </a>
                                                
    
                                            </span>
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

                        </div>
                        <div class="col-lg-8">

                            <div class="row">
                                <div class="col">
                                    <div class="card shadow mb-3">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Kullan??c?? Detaylar??</p>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-body text-center">
                                                    <table class="table table-borderless text-left table-hover table-stripped" style="border-radius:10px;margin:center;">
                                                        <tbody class="text-left">
                                                            <tr>
                                                                <td>Kullan??c?? Ad??</td>
                                                                <td><?php echo $_SESSION['username']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kullan??c?? Id</td>
                                                                <td><?php echo $_SESSION['userid']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>S??n??f D??zeyi</td>
                                                                <td><?php echo ($_SESSION['graduate'] == 13) ? "Mezun" : $_SESSION['graduate']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex">
                                                    <canvas id='lessonGraph' width=300></canvas>
                                                </div>
                                                <div class="text-center small mt-4">
                                                    <span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Matematik</span>
                                                    <span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;T??rk??e</span>
                                                    <span class="mr-2"><i class="fas fa-circle text-info"></i>&nbsp;Geometri</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#BDE4A7;"></i>&nbsp;Kimya</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#7A9CC6;"></i>&nbsp;Fizik</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#9B2226;"></i>&nbsp;Biyoloji</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#BB3E03;"></i>&nbsp;Tarih</span>
                                                    <span class="mr-2"><i class="fas fa-circle " style="color:#005F73;"></i>&nbsp;Co??rafya</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Soru ????z??m Randevusu Al</p>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="container flex-wrap">
                                                <a id="matematik" class="btn mb-3 btn-primary" style="width:120px">Matematik</a>
                                                <a id="turkce" class="btn mb-3 btn-primary" style="width:120px">T??rk??e</a>
                                                <a id="geometri" class="btn mb-3 btn-primary" style="width:120px">Geometri</a>
                                                <a id="fizik" class="btn mb-3 btn-primary" style="width:120px">Fizik</a>
                                                <a id="kimya" class="btn mb-3 btn-primary" style="width:120px">Kimya</a>
                                                <a id="biyoloji" class="btn mb-3 btn-primary" style="width:120px">Biyoloji</a>
                                                <a id="tarih" class="btn mb-3 btn-primary" style="width:120px">Tarih</a>
                                                <a id="cografya" class="btn mb-3 btn-primary" style="width:120px">Co??rafya</a>
                                            </div>
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
                    <div class="text-center my-auto copyright"><span>Copyright ?? Walle 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="../assets/js/chart.min.js"></script>
    <script>
        <?php
        $echolist = "const dis = [";
        if (count($waitlist) == 0) {
            $echolist .= "]";
        } else {
            for ($i = 0; $i < count($waitlist); $i++) {
                if ($i + 1 == count($waitlist)) {
                    $echolist .= "'" . $waitlist[$i] . "']";
                } else {
                    $echolist .= "'{$waitlist[$i]}',";
                }
            }
        }
        $lesanalysis = "const lesanly = [";
        $analysisGraph = new DbConnecter('../src/database/lessonsw.db');
        $asql = "SELECT * FROM statsstudent WHERE ID='{$_SESSION['userid']}'";
        $aresults = $agent->query($asql);
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

        echo $echolist . ';';
        ?>
    </script>
    <script>
        let lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
        console.log(dis)
        for (i of dis) {
            document.getElementById(i).classList.add('disabled');
            document.getElementById(i).onclick = () => {
                alert('Bu derslerden kapat??lmam???? randevunuz bulunmaktad??r.');
            }
            console.log(i);
        };
        for (i of lessons) {
            if (dis.indexOf(i) == -1) {
                document.getElementById(i).href = "./gear.php?reqtype=lesson&lesson=" + i;
                console.log("tika")
            }
        }
        var ctx = document.getElementById("lessonGraph");

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
    <script src="../src/js/winset.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script>
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
    </script>
</body>

</html>