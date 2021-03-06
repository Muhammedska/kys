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
            $sql = "UPDATE teacher SET pp = '{$_GET['imname']}' WHERE ID = '{$ID}';";
            $results = $agent->prepare($sql);
            $res = $results->execute();
            echo "<script>window.location.href='../teacher/user.php?ret=true&reqtype=changepp'</script>";
        } else {
            echo "<script>window.location.href='../teacher/user.php?ret=false&reqtype=changepp'</script>";
        }
    }
    else if ($_GET['reqtype'] == 'del') {
        $analysis = new DbConnecter('../src/database/users.db');
        $sql = "DELETE FROM `teacherreq` WHERE `stid` = '{$_GET['id']}' AND `subject` = '" . $_SESSION['subject'] . "'";
        $results = $analysis->prepare($sql);
        $res = $results->execute();

        $sql = "INSERT INTO `log` (`mission`, `logtm`,`user`) VALUES ('{$_GET['name']} - {$_SESSION['username']} - {$_SESSION['subject']} talebi tamamland??', '{$tarih}','{$_SESSION['username']}');";
        $results = $analysis->prepare($sql);
        $res = $results->execute();

        //$analysisp = new DbConnecter('../src/database/lessonsw.db');
        
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $week = date("W");

        $analysis->exec("INSERT INTO statsteacher (teacher, stname, month, day, year, allt) VALUES ('{$_SESSION['userid']}','{$_GET['name']}','{$month}','{$day}','{$year}','{$tarih}' ) ")or die("<script>window.location.href='../teacher/user.php?ret=false&reqtype=lesson'</script>");
        //$sql = "SELECT * FROM teacher WHERE ID = '{$ID}';";
        //$results = $analysisp->prepare($sql);
        //$res = $results -> execute();
        //$row = $res->fetchArray(SQLITE3_NUM);
        //
        //if($row != false){                       
        //    $analysisp->exec("INSERT INTO t{$ID}(stname, month, day, year, allt) VALUES ('{$_GET['name']}','{$month}','{$day}','{$year}','{$tarih}' ) ")or die("<script>window.location.href='../teacher/user.php?ret=false&reqtype=lesson'</script>");
        //}
        //else{           
        //    $analysisp->exec("CREATE TABLE t{$ID} (stname TEXT, month TEXT, day TEXT, year TEXT, allt TEXT)")or die("<script>window.location.href='../teacher/user.php?ret=false&reqtype=lesson'</script>");
        //    $analysisp->exec("INSERT INTO t{$ID}(stname, month, day, year, allt) VALUES ('{$_GET['name']}','{$month}','{$day}','{$year}','{$tarih}' ) ")or die("<script>window.location.href='../teacher/user.php?ret=false&reqtype=lesson'</script>");
        //    $analysisp->exec("INSERT INTO teacher(ID) VALUES ('{$ID}') ")or die("<script>window.location.href='../teacher/user.php?ret=false&reqtype=lesson'</script>");
        //}
        echo "<script>window.location.href='../teacher/user.php?ret=true&reqtype=delreq&name={$_GET['name']}'</script>";
    }
    else if($_GET['reqtype'] == 'notify'){
        $analysis = new DbConnecter('../src/database/users.db');        
        $sql = "INSERT INTO `notify` (`mass`, `notify`, `sender`) VALUES ('{$_GET['mass']}', '".str_replace("'",'"',$_GET['notifytext'])."', '{$_SESSION['userid']}');";
        $results = $analysis->prepare($sql);
        $res = $results->execute();

        $sql = "INSERT INTO `log` (`mission`, `logtm`,`user`) VALUES ('{$_SESSION['username']} - Bildirim olu??turulma tamamland??', '{$tarih}','{$_SESSION['username']}');";
        $results = $analysis->prepare($sql);
        $res = $results->execute();
        echo "<script>window.location.href='../teacher/user.php?ret=true&reqtype=addnotify'</script>";
    }
    //echo "<script>window.location.href='../teacher/user.php'</script>";
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
