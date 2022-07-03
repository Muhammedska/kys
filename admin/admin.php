<?PHP
session_start();
if ($_SESSION['isactive'] == true) {
    if ($_SESSION['type'] == 'student') {
        echo "<script>window.location.href = '../user/user.php'</script>";
    } elseif ($_SESSION['type'] == 'teacher') {
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

$sql = "SELECT * FROM app WHERE var='token'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$token = $row[1];
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
                    <li class="nav-item"><a class="nav-link" href="./exams.php"><i class="fas fa-edit"></i><span>Sınavlar</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./statics.php"><i class="fas fa-chart-line"></i><span>İstatistikler</span></a></li>
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
                            <?php echo $corp ?>
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
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-warning py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Uygulama Tokeni</span></div>
                                            <div class="text-dark font-weight-bold h6 mb-0"><span id='token'><?php echo $token ?></span></div>
                                        </div>
                                        <div class="col-auto">
                                            <a class='btn ' href="./gear.php?reqtype=app&var=token">
                                                <i class="fas fa-sync fa-2x text-gray-300"></i>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <a class='btn ' onclick='CopyToClipboard("token") ' data-toggle="popover" data-placement="top" title="Kopyalama Başarılı" data-content="<?php echo $token ?>">
                                                <i class="fas fa-copy fa-2x text-gray-300"></i>
                                            </a>
                                        </div>
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
                                <div class="card-body" style="max-height:400px;overflow-y:scroll;">
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
                                    <h6 class="text-primary font-weight-bold m-0">Kayıt Günlüğü</h6>
                                </div>
                                <div class="form-group my-0  p-3">
                                    <input type="text" name="" id="filters" class="form-control" placeholder="Filtre kelime giriniz">
                                </div>
                                <ul class="list-group list-group-flush" id="dataTable" style='max-height:594px;overflow-y:scroll;'>
                                    <?php
                                    $sql = "SELECT * FROM log";
                                    $results = $db->query($sql);
                                    //$results = $res->execute();
                                    $notify = [];
                                    while ($row = $results->fetchArray()) {
                                        array_push($notify, array($row['mission'], $row['logtm'], $row['user']));
                                    };
                                    $con = 1;
                                    if (count($notify) == 0) {
                                        echo "<div class='mb-4 my-4 p-4 text-center'><i class='fa fa-frog' style='font-size:80px;'></i><br> kayıt bulunamadı.</div>";
                                    } else {
                                        foreach (array_reverse($notify) as $key => $value) {
                                            echo '<li class="list-group-item">';
                                            echo '<div class="row align-items-center no-gutters">';
                                            echo '<div class="col mr-2">';
                                            echo '<h6 class="mb-0 text-dark"><strong>' . $value[0] . '</strong></h6><span class="text-xs">' . $value[1] . '</span>';
                                            echo '</div>';
                                            echo '</div></li>';
                                            $con++;
                                        }
                                    }
                                    ?>


                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Kişiselleştirme</h6>
                                </div>
                                <div class="card-body">
                                    <p class="m-0">Kurum İsmi</p>
                                    <form action="./gear.php" method="get" class=' container-fluid'>
                                        <input type="text" name="reqtype" style='display:none' value='app'>
                                        <input type="text" name="var" style='display:none' value='name'>
                                        <input type="text" name="value" class="form-control" placeholder="Kurum ismi giriniz" value='<?php echo $corp ?>'>
                                        <button class="btn btn-primary" type="submit">Güncelle</button>
                                    </form>
                                    <hr>
                                    <span>
                                        <span class='btn-lg disable'>Resim Slaytı <?php echo ($carousel == 'active') ? 'Aktif' : 'Pasif'; ?></span>
                                        &nbsp;&nbsp;<a class='btn btn-lg btn-<?php echo ($carousel == 'active') ? 'warning' : 'secondary'; ?>' href='./gear.php?reqtype=app&var=carousel&key=<?php echo ($carousel == 'active') ? 'pasif' : 'active'; ?>'><i class="fa fa-lightbulb" aria-hidden="true"></i></a>
                                    </span>
                                    <hr>
                                    <span>
                                        <span class='btn-lg disable'>Bildirimler <?php echo ($notshow == 'active') ? 'Aktif' : 'Pasif'; ?></span>
                                        &nbsp;&nbsp;<a class='btn btn-lg btn-<?php echo ($notshow == 'active') ? 'warning' : 'secondary'; ?>' href='./gear.php?reqtype=app&var=notify&key=<?php echo ($notshow == 'active') ? 'pasif' : 'active'; ?>'><i class="fa fa-lightbulb" aria-hidden="true"></i></a>
                                    </span>
                                    <hr>
                                    <h3>Neden Biz? Bölümü</h3>
                                    <div class='row container-fluid'>
                                        <div class='col my-0'>
                                            <p>1. Kısım</p>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='why1'>
                                                <textarea name="value" class="form-control" placeholder="Neden bölümü giriniz"><?php echo $why1 ?></textarea>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                        <div class='col my-0'>
                                            <p>2. Kısım</p>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='why2'>
                                                <textarea name="value" class="form-control" placeholder="Neden bölümü giriniz"><?php echo $why2 ?></textarea>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class='row container-fluid'>
                                        <div class='col my-0'>
                                            <p>3. Kısım</p>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='why3'>
                                                <textarea name="value" class="form-control" placeholder="Neden bölümü giriniz"><?php echo $why3 ?></textarea>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                        <div class='col my-0'>
                                            <p>4. Kısım</p>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='why4'>
                                                <textarea name="value" class="form-control" placeholder="Neden bölümü giriniz"><?php echo $why4 ?></textarea>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                    </div>
                                    <hr>
                                    <h3>Map & İletişim Bölümü</h3>
                                    <div class='row container-fluid'>
                                        <div class='col my-0'>
                                            <label>Telefon numarası:</label>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='phone'>
                                                <input type='phone' name="value" class="form-control" placeholder="Telefon numarası giriniz" value='<?php echo $phone ?>'></input>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                        <div class='col my-0'>
                                            <label>E-posta:</label>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='email'>
                                                <input type='email' name="value" class="form-control" placeholder="Telefon numarası giriniz" value='<?php echo $email ?>'></input>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class='row container-fluid'>
                                        <div class='col my-0'>
                                            <label>Adres:</label>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='address'>
                                                <textarea name="value" class="form-control" placeholder="Açık adres giriniz"><?php echo $address ?></textarea>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                        <div class='col my-0'>
                                            <label>Google maps konum linki:</label>
                                            <form action="./gear.php" method="get" class=' container-fluid'>
                                                <input type="text" name="reqtype" style='display:none' value='app'>
                                                <input type="text" name="var" style='display:none' value='map'>
                                                <input type='text' name="value" class="form-control" placeholder="google maps ten konumun linkini koyunuz" value='<?php echo $map ?>'></input>
                                                <button class="btn btn-primary" type="submit">Güncelle</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3 my-4">
                            <div class="card shadow mb-3">
                                <div class="card-header py-3">
                                    <h6 class="text-primary font-weight-bold m-0">Resim Slaytı</h6>
                                </div>
                                <div class='card-body'>
                                    <div class='form-group'>
                                        <form action="./gear.php" method="post" enctype="multipart/form-data">
                                            Select image to upload:
                                            <input type="text" name="reqtype" value="fileupload" style="display:none;">
                                            <input type="file" name="fileToUpload" id="fileToUpload">
                                            <input type="submit" value="Upload Image" name="submit">
                                        </form>
                                    </div>
                                    <div class="my-4" style="max-height:600px;overflow-y:scroll;overflow-x:scroll;">
                                        <table class="table table-borderless text-left table-hover table-stripped" style="border-radius:10px;margin:center;">
                                            <thead style='position:sticky'>
                                                <tr>
                                                    <th width=60>#</th>
                                                    <th width=100>Resim</th>
                                                    <th>İşlem</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-left">
                                                <?php
                                                $examdir = "../src/img/carousel/";
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
                                                        echo '<tr>';
                                                        echo '<td>' . ($i + 1) . '</td>';
                                                        echo '<td><img src="../src/img/carousel/' . $diffar[$i] . '" width=100></td>';
                                                        echo '<td>
                                                    <form method="get" action="./gear.php">
                                                    <input type="text" name="reqtype" value="caro" style="display:none">
                                                    <input type="text" name="type" value="update" style="display:none">
                                                    <input type="text" name="newname" value="' . str_replace(['.jpeg', '.jpg', '.png'], '', $diffar[$i]) . '" >
                                                    <input type="text" name="oldname" value="' . $diffar[$i] . '" style="display:none">
                                                    <button class="btn btn-primary">Güncelle</button>
                                                    </form>
                                                    <a href="./gear.php?reqtype=caro&type=del&imfile=' . $diffar[$i] . '" class="btn btn-danger">Sil</a>
                                                    </td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
        function CopyToClipboard(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            $(document).ready(function() {
                $('[data-toggle="popover"]').popover();
                setTimeout(function() {
                    $('[data-toggle="popover"]').popover('hide');
                }, 1000);
            });
        }
    </script>
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

</body>

</html>