<?php
session_start();

class DbConnecter extends SQLite3
{
    function __construct($path)
    {
        $this->open($path);
    }
}
if (empty($_GET['userid'])) {
    echo "<script>window.location.href='./login.html'</script>";
}
if ($_GET['logintype'] == "student") {
    $ID = $_GET['userid'];
    $agent = new DbConnecter('./src/database/users.db');
    $sql = "SELECT * FROM student WHERE ID = '{$ID}';";
    $results = $agent->prepare($sql);
    $res = $results->execute();
    $row = $res->fetchArray(SQLITE3_NUM);

    if ($row != false) {
        //echo "{$row[0]} {$row[1]} {$row[2]} {$row[3]}";
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = 'student';
        $_SESSION['userid'] = $row[0];
        $_SESSION['username'] = $row[1];
        $_SESSION['graduate'] = $row[2];

        if ($row[3] == 'peter') {
            $_SESSION['pp'] = "../assets/img/dogs/image2.jpeg";
        } else if ($row[3] == 'franko') {
            $_SESSION['pp'] = "../assets/img/dogs/image3.jpeg";
        } else if ($row[3] == 'ralph') {
            $_SESSION['pp'] = "../assets/img/dogs/image4.jpeg";
        } else if ($row[3] == 'jessi') {
            $_SESSION['pp'] = "../assets/img/dogs/image5.jpeg";
        } else if ($row[3] == 'leo') {
            $_SESSION['pp'] = "../assets/img/dogs/image6.jpeg";
        } else if ($row[3] == 'mike') {
            $_SESSION['pp'] = "../assets/img/dogs/image7.jpeg";
        }

        setcookie('sessionisopened', 'true', 0, "/");
        setcookie('type', 'student', 0, "/");
        setcookie('userid', $row[0], 0, "/");
        setcookie('username', $row[1], 0, "/");
        setcookie('graduate', $row[2], 0, "/");
        setcookie('pp', $_SESSION['pp'], 0, "/");
        
        $_COOKIE['type'] = 'student';
        $_COOKIE['userid'] = $row[0];
        $_COOKIE['username'] = $row[1];
        $_COOKIE['graduate'] = $row[2];
        $_COOKIE['pp'] = $_SESSION['pp'];
        $_COOKIE['sessionisopened'] = 'true';
        echo "<script>window.location.href='./user/user.php'</script>";
    } else {
        echo "<script>window.location.href='./login.html'</script>";
    }
}elseif($_GET['logintype'] == "teacher"){
    $ID = $_GET['userid'];
    $agent = new DbConnecter('./src/database/users.db');
    $sql = "SELECT * FROM teacher WHERE ID = '{$ID}';";
    $results = $agent->prepare($sql);
    $res = $results->execute();
    $row = $res->fetchArray(SQLITE3_NUM);

    if ($row != false) {
        //echo "{$row[0]} {$row[1]} {$row[2]} {$row[3]}";
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = 'teacher';
        $_SESSION['userid'] = $row[0];
        $_SESSION['username'] = $row[1];
        $_SESSION['subject'] = $row[2];

        if ($row[3] == 'peter') {
            $_SESSION['pp'] = "../assets/img/dogs/image2.jpeg";
        } else if ($row[3] == 'franko') {
            $_SESSION['pp'] = "../assets/img/dogs/image3.jpeg";
        } else if ($row[3] == 'ralph') {
            $_SESSION['pp'] = "../assets/img/dogs/image4.jpeg";
        } else if ($row[3] == 'jessi') {
            $_SESSION['pp'] = "../assets/img/dogs/image5.jpeg";
        } else if ($row[3] == 'leo') {
            $_SESSION['pp'] = "../assets/img/dogs/image6.jpeg";
        } else if ($row[3] == 'mike') {
            $_SESSION['pp'] = "../assets/img/dogs/image7.jpeg";
        }
        setcookie('type', 'teacher',0, "/");
        setcookie('userid', $row[0],0, "/");
        setcookie('username', $row[1],0, "/");
        setcookie('subject', $row[2],0, "/");
        setcookie('pp', $_SESSION['pp'],0, "/");
        setcookie('sessionisopened', 'true',0, "/");

        $_COOKIE['type'] = 'teacher';
        $_COOKIE['userid'] = $row[0];
        $_COOKIE['username'] = $row[1];
        $_COOKIE['subject'] = $row[2];
        $_COOKIE['pp'] = $_SESSION['pp'];
        $_COOKIE['sessionisopened'] = 'true';
        echo "<script>window.location.href='./teacher/user.php'</script>";
    } else {
        echo "<script>window.location.href='./login.html'</script>";
    }
}else if ($_GET['logintype'] == "kurum") {
    if($_GET['password'] == "kurum" && $_GET['userid'] == "kurum"){
        $_SESSION['isactive'] = true;
        $_SESSION['type'] = 'kurum';
        $_SESSION['userid'] = "kurum";
        $_SESSION['username'] = "kurum";
        $_SESSION['graduate'] = "kurum";
        $_SESSION['pp'] = "../assets/img/dogs/image8.jpeg";
        setcookie('type', 'kurum', 0, "/");
        setcookie('userid', 'kurum', 0, "/");
        setcookie('username', 'kurum', 0, "/");
        setcookie('graduate', 'kurum', 0, "/");
        setcookie('pp', '../assets/img/dogs/image8.jpeg', 0, "/");
        setcookie('sessionisopened', 'true', 0, "/");
        $_COOKIE['type'] = 'kurum';
        $_COOKIE['userid'] = "kurum";
        $_COOKIE['username'] = "kurum";
        $_COOKIE['graduate'] = "kurum";
        $_COOKIE['pp'] = $_SESSION['pp'];
        $_COOKIE['sessionisopened'] = 'true';
        echo "<script>window.location.href='./admin/admin.php'</script>";
    }else{
        echo "<script>window.location.href='./login.html'</script>";
    }
}
?>