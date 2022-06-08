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
$IDS = '[';
$NS = '[';
while ($row = $results->fetchArray()) {
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
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'],$row['pp'])];
    array_push($datam, array($row['ID'], $row['name'], $row['lesson'],$row['pp']));
};

$IDS .= ']';
$NS .= ']';
echo "<script>const IDS = $IDS; const NS = $NS;</script>";

$examdir = "../src/video/exams/";
$folders = scandir($examdir);
sort($folders);
array_reverse($folders);
$folders = array_diff($folders, [".", ".."]);
if (empty($_GET['list'])) {
    $_GET['list'] = 'student';
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
                    <hr class="sidebar-divider my-0">
                    <li class="nav-item"><a class="nav-link <?php echo ($_GET['list'] == 'student') ? 'active' : ''; ?>" href="./table.php?list=student"><i class="fas fa-table"></i><span>Öğrenci</span></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($_GET['list'] == 'teacher') ? 'active' : ''; ?>" href="./table.php?list=teacher"><i class="fas fa-table"></i><span>Öğretmen</span></a></li>

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

                    <h3 class="text-dark mb-4">Listeler</h3>

                    <div class="row mb-3">

                        <div class="col-lg-12">
                            <div class="card mb-3" style="min-height:382px;">
                                <div class="card-header">
                                    <input type="text" id="filters" class="form-control " placeholder="Aranan İsim">
                                </div>
                                <div class="card-body text-left shadow" style="overflow-x:scroll;">
                                    <table class="table my-0" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>İsim</th>
                                                <th>ID</th>
                                                <th>Sınıf / Branş</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($_GET['list'] == 'student' || empty($_GET['list'])) {

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
                                                    echo "<tr>";
                                                    echo "<td>" . '<img class="rounded-circle mr-2" width="30" height="30" src=' . $pp . '>' . "{$datar[$i][1]}</td>";
                                                    echo "<td>{$datar[$i][0]}</td>";
                                                    echo "<td>{$datar[$i][2]}</td>";
                                                    echo "<td>
                                                    <a href='./gear.php?id={$datar[$i][0]}&reqtype=del' class='text-danger'><i class='fa fa-trash'></i></a> &nbsp;
                                                    <a href='./gear.php?id={$datar[$i][0]}&reqtype=del' class='text-success'><i class='fas fa-chart-line'></i></a>
                                                    </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
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
                                                    echo "<tr>";
                                                    echo "<td>" . '<img class="rounded-circle mr-2" width="30" height="30" src=' . $pp . '>' . "{$datam[$i][1]}</td>";
                                                    echo "<td>{$datam[$i][0]}</td>";
                                                    echo "<td>{$datam[$i][2]}</td>";
                                                    echo "<td>
                                                    <a href='./gear.php?id={$datam[$i][0]}&reqtype=del' class='text-danger'><i class='fa fa-trash'></i></a>
                                                    <a href='./gear.php?id={$datam[$i][0]}&reqtype=del' class='text-danger'><i class='fas fa-chart-line'></i></a>
                                                    </td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>İsim</strong></td>
                                                <td><strong>ID</strong></td>
                                                <td><strong>Sınıf / branş</strong></td>
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
                $("#dataTable tr").filter(function() {
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