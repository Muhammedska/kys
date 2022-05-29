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
$IDS = '[';
while ($row = $results->fetchArray()) {
    $datar += [array($row['ID'], $row['name'], $row['grade'], $row['pp'])];
    $IDS .= $row['ID'] . ',';
};

$sql = "SELECT * FROM teacher ORDER BY ID";
$results = $db->query($sql);
$datat = [];
while ($row = $results->fetchArray()) {
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'])];
};
echo $IDS;
$IDS .= ']';
echo "<script>var IDS = $IDS;</script>";

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
                    <li class="nav-item"><a class="nav-link" href="./admin.php"><i class="fas fa-home"></i><span>Ana Sayfa</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="./adds.php"><i class="fas fa-user-plus"></i><span>Öğrenci Ekle</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="./addt.php"><i class="fas fa-user-plus"></i><span>Öğretmen Ekle</span></a></li>
                    <div id="accordion">
                        <div class="nav-item bg-transparent">
                            <div class="nav-item">
                                <a class="nav-link" data-toggle="collapse" href="#collapseOne">
                                    Kullanıcı listesi
                                </a>
                            </div>
                            <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                <div class="nav-item">
                                    <li class="nav-item"><a class="nav-link" href="./table.php?list=student"><i class="fas fa-table"></i><span>Öğrenci</span></a></li>
                                    <li class="nav-item"><a class="nav-link" href="./table.php?list=teacher"><i class="fas fa-table"></i><span>Öğretmen</span></a></li>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            if ($_GET['reqtype'] == 'changepp') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Profil resminiz başarıyle değiştirildi. 👍</div>";
                            } else if ($_GET['reqtype'] == 'lesson') {
                                echo "<div class='alert alert-success fade show alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <strong>{$_SESSION['username']}</strong> Soru çözüm randevu talebiniz başarıyla alınmıştır. 👍</div>";
                            }
                        }
                    }
                    ?>

                    <h3 class="text-dark mb-4">Profil</h3>

                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-body text-left shadow">
                                    <form action="/action_page.php" class="was-validated">
                                        <div class="form-group">
                                            <label for="uname">Öğrenci İsmi:</label>
                                            <input type="text" class="form-control" id="uname" placeholder="Öğrencinin Adı ve Soyadı" name="uname" required>
                                            <div class="valid-feedback">Geçerli.</div>
                                            <div class="invalid-feedback">Bu alan doldurulmalı.</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pwd">Öğrenci ID:</label>
                                            <input type="text" class="form-control" id="pwd" placeholder="Öğrenci Numarası" name="pswd" required onforminput="checkval()">
                                            <div class="valid-feedback">Geçerli.</div>
                                            <div class="invalid-feedback">Bu alan doldurulmalı.</div>
                                            <div id="crash" style="display:none;">Bu öğrenci numarası daha öncesinde eklenmiş.</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="studentstatus">Öğrenci Sınıf Düzeyi:</label>
                                            <select class="form-control" id="studentstatus" placeholder="Öğrenci Numarasını Giriniz" name="studentstatus" required>
                                                <option value="9">9.Sınıf</option>
                                                <option value="10">10.Sınıf</option>
                                                <option value="11">11.Sınıf</option>
                                                <option value="12">12.Sınıf</option>
                                                <option value="13">Mezun</option>
                                            </select>
                                        </div>
                                        <input type="text" value="franko" name="pp" style="display:none;">
                                        <button type="submit" class="btn btn-primary" id="addstd">Öğrenci Ekle</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card mb-3" style="min-height:382px;">
                                <div class="card-header">
                                    <input type="text" id="filters" class="form-control " placeholder="Aranan İsim">
                                </div>
                                <div class="card-body text-left shadow">
                                    <table class="table my-0" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>İsim</th>
                                                <th>ID</th>
                                                <th>Sınıf</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
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
                                                echo "<td><a href='./gear.php?id={$datar[$i][0]}&reqtype=del' class='text-danger'><i class='fa fa-trash'></i></a></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>İsim</strong></td>
                                                <td><strong>ID</strong></td>
                                                <td><strong>Sınıf</strong></td>
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
                var value = $(this).val().toLowerCase();
                if (IDS.indexOf(value) != -1) {
                    document.getElementById("crash").style.display = "";
                    document.getElementById("addstd").disabled = true;
                } else {
                    document.getElementById("crash").style.display = "none";
                    document.getElementById("addstd").disabled = false;
                }
            });
        });
        console.log(IDS)

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