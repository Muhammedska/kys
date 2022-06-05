<?PHP
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'kurum') {
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
                    <li class="nav-item"><a class="nav-link active" href="./admin.php"><i class="fas fa-home"></i><span>Ana Sayfa</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./adds.php"><i class="fas fa-user-plus"></i><span>Öğrenci Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./addt.php"><i class="fas fa-user-plus"></i><span>Öğretmen Ekle</span></a></li>
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

                <div class="container-fluid">
                    <div class="container-fluid text-left mb-4">
                        <?php
                        if (!empty($_GET["ret"])) {
                            if ($_GET['ret'] == "true") {
                                if ($_GET['reqtype'] == 'addnotify') {
                                    echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni Bildirim başarıyla eklendi. 👍</div>";
                                } else if ($_GET['reqtype'] == 'delreq') {
                                    echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Bildirim başarıyla silindi. 👍</div>";
                                }
                            } else {
                                if ($_GET['reqtype'] == 'addt') {
                                    echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Yeni Öğretmen eklenemedi. 🤔</div>";
                                } else if ($_GET['reqtype'] == 'del') {
                                    echo "<div class='alert alert-danger fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert' onclick='window.location.href=`#`'>&times;</button> <strong>{$_SESSION['username']}</strong> Öğretmen silinemedi. 🤔</div>";
                                }
                            }
                        }
                        ?>
                        <h3 class="text-dark mb-0">Yönetim Paneli</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Kayıtlı Öğrenci</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo count($datar); ?></span></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Deneme Sayısı</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo count($folders) ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-warning py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Öğretmen Sayısı</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo count($datat) ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user-alt fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 col-xl-8">
                            <div class="card shadow mb-4">
                                <?php
                                $sql = "SELECT * FROM notify ORDER BY notify";
                                $results = $db->query($sql);
                                $notify = [];
                                while ($row = $results->fetchArray()) {
                                    array_push($notify, array($row['mass'], $row['notify'], $row['sender']));
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
                                            if ($value[2] == "kurum") {
                                                echo "<div class='media'>
                                            <span class='align-self-center dropdown no-arrow ' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                <a class='btn btn-link btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false' type='button'>
                                                    <img src='../assets/img/dogs/image8.jpeg' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                </a>
                                                <div class='dropdown-menu shadow dropdown-menu-right animated--fade-in'>
                                                    <p class='text-center dropdown-header'>İşlemler:</p>
                                                    <a class='dropdown-item text-danger' href='./gear.php?reqtype=notify&sender={$value[2]}&text={$value[1]}&mass={$value[0]}&type=del'><i class='fa fa-trash'></i>&nbsp;Sil</a>                                                    
                                                </div>
    
                                            </span>
                                            <div class='media-body p-4'>
                                                <h4>{$value[2]}</h4>
                                                <p>{$value[1]}</p>
                                            </div>
                                        </div>";
                                            } else {
                                                $i = $value[2];
                                                if ($datat[$i][3] == 'peter') {
                                                    $pp = "../assets/img/dogs/image2.jpeg";
                                                } else if ($datat[$i][3] == 'franko') {
                                                    $pp = "../assets/img/dogs/image3.jpeg";
                                                } else if ($datat[$i][3] == 'ralph') {
                                                    $pp = "../assets/img/dogs/image4.jpeg";
                                                } else if ($datat[$i][3] == 'jessi') {
                                                    $pp = "../assets/img/dogs/image5.jpeg";
                                                } else if ($datat[$i][3] == 'leo') {
                                                    $pp = "../assets/img/dogs/image6.jpeg";
                                                } else if ($datat[$i][3] == 'mike') {
                                                    $pp = "../assets/img/dogs/image7.jpeg";
                                                }
                                                echo "<div class='media'>
                                            <span class='align-self-center dropdown no-arrow ' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                <a class='btn btn-link btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false' type='button'>
                                                    <img src='{$pp}' class='align-self-center mr-3 rounded-circle' style='width:60px;height:60px;'>
                                                </a>
                                                <div class='dropdown-menu shadow dropdown-menu-right animated--fade-in'>
                                                    <p class='text-center dropdown-header'>İşlemler:</p>
                                                    <a class='dropdown-item text-danger' href='./gear.php?reqtype=notify&sender={$value[2]}&text={$value[1]}&mass={$value[0]}&type=del'><i class='fa fa-trash'></i>&nbsp;Sil</a>                                                    
                                                </div>
    
                                            </span>
                                            <div class='media-body p-4'>
                                                <h4>{$datat[$i][1]} - {$datat[$i][2]}</h4>
                                                <p>{$value[1]}</p>
                                            </div>
                                        </div>";
                                            }
                                        }
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xl-4">
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
                                            <label class="form-check-label mb-2 my-3 " style="margin-right:30px;">
                                                <input type="radio" class="form-check-input" name="mass" id="notifytypeteacher" value="teacher">
                                                Öğretmen
                                            </label>
                                            <label class="form-check-label mb-2 my-3 " style="margin-right:30px;">
                                                <input type="radio" class="form-check-input" name="mass" id="notifytypeall" value="all">
                                                Genel
                                            </label>
                                        </div>
                                        <input type="text" name="reqtype" value="notify" style="display:none;">
                                        <input type="text" name="type" value="add" style="display:none;">
                                        <button class="btn btn-primary" type="submit">Bildirim Oluştur</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Projects</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Server migration<span class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"><span class="sr-only">20%</span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales tracking<span class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"><span class="sr-only">40%</span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database<span class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-primary" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60%</span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details<span class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"><span class="sr-only">80%</span></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account setup<span class="float-right">Complete!</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100%</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Todo List</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <h6 class="mb-0"><strong>Lunch meeting</strong></h6><span class="text-xs">10:30 AM</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="formCheck-1"><label class="custom-control-label" for="formCheck-1"></label></div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <h6 class="mb-0"><strong>Lunch meeting</strong></h6><span class="text-xs">11:30 AM</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="formCheck-2"><label class="custom-control-label" for="formCheck-2"></label></div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col mr-2">
                                                <h6 class="mb-0"><strong>Lunch meeting</strong></h6><span class="text-xs">12:30 AM</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" id="formCheck-3"><label class="custom-control-label" for="formCheck-3"></label></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-primary shadow">
                                        <div class="card-body">
                                            <p class="m-0">Primary</p>
                                            <p class="text-white-50 small m-0">#4e73df</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-success shadow">
                                        <div class="card-body">
                                            <p class="m-0">Success</p>
                                            <p class="text-white-50 small m-0">#1cc88a</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-info shadow">
                                        <div class="card-body">
                                            <p class="m-0">Info</p>
                                            <p class="text-white-50 small m-0">#36b9cc</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-warning shadow">
                                        <div class="card-body">
                                            <p class="m-0">Warning</p>
                                            <p class="text-white-50 small m-0">#f6c23e</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-danger shadow">
                                        <div class="card-body">
                                            <p class="m-0">Danger</p>
                                            <p class="text-white-50 small m-0">#e74a3b</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-secondary shadow">
                                        <div class="card-body">
                                            <p class="m-0">Secondary</p>
                                            <p class="text-white-50 small m-0">#858796</p>
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
</body>

</html>