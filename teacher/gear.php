<?php


session_start();

$ppnames = ["peter", "franko", "ralph", "jessi", "leo", "mike"];

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
        }else{
            echo "<script>window.location.href='../teacher/user.php?ret=false&reqtype=changepp'</script>";
        }
    }if($_GET['reqtype'] == 'del'){
        $analysis = new DbConnecter('../src/database/users.db');        
        $sql = "DELETE FROM `teacherreq` WHERE `stid` = '{$_GET['id']}' AND `subject` = '" . $_SESSION['subject'] . "'";
        $results = $analysis->prepare($sql);
        $res = $results -> execute();       
        echo "<script>window.location.href='../teacher/user.php?ret=true&reqtype=delreq&name={$_GET['name']}'</script>";
    }
    //echo "<script>window.location.href='../user/user.php'</script>";
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
