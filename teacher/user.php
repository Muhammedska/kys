<?php
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'student') {
        echo "<script>window.location.href = '../user/user.php'</script>";
    } elseif ($_SESSION['type'] == 'teacher') {
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
$ID = $_SESSION['subject'];
$agent = new DbConnecter('../src/database/users.db');
$lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
$sql = "SELECT * FROM teacherreq WHERE subject = '{$ID}';";
$results = $agent->prepare($sql);
$res = $results->execute();
//$row = $res->fetchArray(SQLITE3_NUM);
//var_dump($row);
$waitlist = [];
while ($row = $res->fetchArray()) {
    //var_dump($row);    
    array_push($waitlist, array($row[0], $row[1], $row[2], $row[3]));
}
if (empty($_GET['grade'])) {
    $_GET['grade'] = "9";
}

$db = new DbConnecter('../src/database/users.db');
$sql = "SELECT * FROM teacher ORDER BY ID";
$results = $db->query($sql);
$datat = [];
while ($row = $results->fetchArray()) {
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'], $row['pp'])];
};

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

                    <li class="nav-item"><a class="nav-link active" href="../user/user.php"><i class="fas fa-user"></i><span>Profil</span></a></li>
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
                        <!-- <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                            </div>
                        </form> -->
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
                            } else if ($_GET['reqtype'] == 'delreq') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_GET['name']}</strong> Soru çözüm randevu talebi başarıyla alınmıştır. 👍</div>";
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
                                            Resmi Değiştir
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
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Bildirim Oluştur</h6>
                                </div>
                                <div class="card-body">
                                    <form action="./gear.php" method="get">
                                        <label for="notifytext">Bildirim Metni :</label>
                                        <textarea name="notifytext" id="text" cols="30" rows="5" class="form-control" placeholder="Bildirim metni giriniz" required></textarea>
                                        <div class="form-check flex-wrap container text-center">
                                            <label class="form-check-label mb-2 my-3 " style="margin-right:30px;">
                                                <input type="radio" class="form-check-input" name="mass" id="notifytypestudent" value="student" checked>
                                                Öğrenci
                                            </label>

                                        </div>
                                        <input type="text" name="reqtype" value="notify" style="display:none;">
                                        <input type="text" name="type" value="add" style="display:none;">
                                        <button class="btn btn-primary" type="submit">Bildirim Oluştur</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <?php
                                $sql = "SELECT * FROM notify ORDER BY notify";
                                $results = $db->query($sql);
                                $notify = [];
                                while ($row = $results->fetchArray()) {
                                    if($row['mass'] == 'teacher'){
                                        
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
                                            <p class="text-primary m-0 font-weight-bold">Kullanıcı Detayları</p>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-body text-center">
                                                    <table class="table table-borderless text-left table-hover table-stripped">
                                                        <tbody class="text-left">
                                                            <tr>
                                                                <td>Kullanıcı Adı</td>
                                                                <td><?php echo $_SESSION['username']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kullanıcı Id</td>
                                                                <td><?php echo $_SESSION['userid']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Branş</td>
                                                                <td><?php echo $_SESSION['subject']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card shadow" id="q">
                                        <div class="card-header py-3">
                                            <p class="text-primary m-0 font-weight-bold">Soru Çözüm Randevusu Alanlar</p>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="container flex-wrap text-center">
                                                <a href="./user.php?grade=9#q" class=" btn<?php echo $retVal = ($_GET['grade'] == "9" || empty($_GET['grade'])) ? "" : "-outline"; ?>-primary btn mb-3" style="width:120px">9.Sınıf</a>&nbsp;
                                                <a href="./user.php?grade=10#q" class=" btn<?php echo $retVal = ($_GET['grade'] == "10") ? "" : "-outline"; ?>-primary btn mb-3" style="width:120px">10.Sınıf</a>&nbsp;
                                                <a href="./user.php?grade=11#q" class=" btn<?php echo $retVal = ($_GET['grade'] == "11") ? "" : "-outline"; ?>-primary btn mb-3" style="width:120px">11.Sınıf</a>&nbsp;
                                                <a href="./user.php?grade=12#q" class=" btn<?php echo $retVal = ($_GET['grade'] == "12") ? "" : "-outline"; ?>-primary btn mb-3" style="width:120px">12 & Mezun</a>
                                            </div>
                                            <div class='form-group'>
                                                <input type="text" class="form-control " placeholder="ID veya isim giriniz">
                                            </div>
                                            <div>
                                                <table class="table my-0" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>İsim</th>
                                                            <th>ID</th>
                                                            <th>İşlem</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        //echo !empty($_GET['grade']);
                                                        if ($_GET['grade'] == "9" && !empty($_GET['grade'])) {
                                                            $sql = "SELECT * FROM `teacherreq` WHERE `graduate` = '9' AND `subject` = '" . $_SESSION['subject'] . "'";
                                                        } else if ($_GET['grade'] == "10") {
                                                            $sql = "SELECT * FROM `teacherreq` WHERE `graduate` = '10' AND `subject` = '" . $_SESSION['subject'] . "'";
                                                        } else if ($_GET['grade'] == "11") {
                                                            $sql = "SELECT * FROM `teacherreq` WHERE `graduate` = '11' AND `subject` = '" . $_SESSION['subject'] . "'";
                                                        } else if ($_GET['grade'] == "12") {
                                                            $sql = "SELECT * FROM `teacherreq` WHERE `graduate` = '12' AND `subject` = '" . $_SESSION['subject'] . "'";
                                                        } else {
                                                        }
                                                        //$sql = "SELECT * FROM teacherreq WHERE subject = '{$ID}';";
                                                        $results = $agent->prepare($sql);
                                                        $res = $results->execute();

                                                        //var_dump($res);
                                                        //$row = $res->fetchArray(SQLITE3_NUM);
                                                        //var_dump($row);
                                                        $datar = [];
                                                        while ($row = $res->fetchArray()) {
                                                            //var_dump($row);    
                                                            array_push($datar, array($row[0], $row[1], $row[2], $row[3]));
                                                        }
                                                        for ($i = 0; $i < count($datar); $i++) {

                                                            echo "<tr>";
                                                            echo "<td>"  . "{$datar[$i][1]}</td>";
                                                            echo "<td>{$datar[$i][0]}</td>";
                                                            //echo "<td>{$datar[$i][2]}</td>";
                                                            echo "<td><a href='./gear.php?id={$datar[$i][1]}&name={$datar[$i][0]}&reqtype=del&subject={$_SESSION['subject']}' class='text-danger'><i class='fa fa-trash'></i></a></td>";
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>ID</strong></td>
                                                            <td><strong>İsim</strong></td>
                                                            <td><strong>İşlem</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>

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
    <script>
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
    </script>
</body>

</html>