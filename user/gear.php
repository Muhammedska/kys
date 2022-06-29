<?php


session_start();

$ppnames = ["peter", "franko", "ralph", "jessi", "leo", "mike"];
$tarih = date("Y-m-d h:i:sa");
if ($_SESSION['isactive']) {
    class DbConnecter extends SQLite3
    {
        function __construct($path)
        {
            $this->open($path);
        }
    }
    $ID = $_SESSION['userid'];
    $agent = new DbConnecter('../src/database/users.db');
    if ($_GET['reqtype'] == 'changepp') {
        if (in_array($_GET['imname'], $ppnames)) {
            if ($_GET['imname'] == 'peter') {
                $_SESSION['pp'] = "../assets/img/dogs/image2.jpeg";
            } else if ($_GET['imname'] == 'franko') {
                $_SESSION['pp'] = "../assets/img/dogs/image3.jpeg";
            } else if ($_GET['imname'] == 'ralph') {
                $_SESSION['pp'] = "../assets/img/dogs/image4.jpeg";
            } else if ($_GET['imname'] == 'jessi') {
                $_SESSION['pp'] = "../assets/img/dogs/image5.jpeg";
            } else if ($_GET['imname'] == 'leo') {
                $_SESSION['pp'] = "../assets/img/dogs/image6.jpeg";
            } else if ($_GET['imname'] == 'mike') {
                $_SESSION['pp'] = "../assets/img/dogs/image7.jpeg";
            }
            $sql = "UPDATE student SET pp = '{$_GET['imname']}' WHERE ID = '{$ID}';";
            $results = $agent->prepare($sql);
            $res = $results->execute();
            echo "<script>window.location.href='../user/user.php?ret=true&reqtype=changepp'</script>";
        }else{
            echo "<script>window.location.href='../user/user.php?ret=false&reqtype=changepp'</script>";
        }
    }if($_GET['reqtype'] == 'lesson'){
        $analysis = new DbConnecter('../src/database/lessonsw.db');
        $analysist = new DbConnecter('../src/database/users.db');
        $sql = "SELECT * FROM inlist WHERE stid = '{$ID}';";
        $results = $analysis->prepare($sql);
        $res = $results -> execute();
        $row = $res->fetchArray(SQLITE3_NUM);
        
        if($row != false){
            $agent->exec("INSERT INTO teacherreq(sname,stid,subject,graduate) VALUES ('{$_SESSION['username']}','{$_SESSION['userid']}','{$_GET['lesson']}','{$_SESSION['graduate']}')") or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");    
            $analysis->exec("INSERT INTO l{$ID}(lesson) VALUES ('{$_GET['lesson']}') ")or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");
        }
        else{
            $agent->exec("INSERT INTO teacherreq(sname,stid,subject,graduate) VALUES ('{$_SESSION['username']}','{$_SESSION['userid']}','{$_GET['lesson']}','{$_SESSION['graduate']}')") or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");
            $analysis->exec("CREATE TABLE l{$ID} (lesson TEXT)")or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");
            $analysis->exec("INSERT INTO l{$ID}(lesson) VALUES ('{$_GET['lesson']}') ")or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");
            $analysis->exec("INSERT INTO inlist(stid) VALUES ('{$ID}') ")or die("<script>window.location.href='../user/user.php?ret=false&reqtype=lesson'</script>");
        }

        $sql = "INSERT INTO `log` (`mission`, `logtm`,`user`) VALUES ('{$_SESSION['username']} - {$_GET['lesson']} randevu talebi oluşturma tamamlandı', '{$tarih}','{$_SESSION['username']}');";
        //$results = $analysis->prepare($sql);
        $analysist->exec($sql);
        //$res = $results->execute();
        echo "<script>window.location.href='../user/user.php?ret=true&reqtype=lesson'</script>";
    }else {
        echo "<script>window.location.href='../user/user.php'</script>";
        //
    }
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
