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
    if ($_SESSION["type"] == "kurum") {
        if ($_GET['reqtype'] == "add") {
            $agent = new DbConnecter('../src/database/users.db');
            $sql = "INSERT INTO student (ID, name, grade, pp) VALUES ('{$_GET['pswd']}', '{$_GET['uname']}', '{$_GET['studentstatus']}', '{$_GET['pp']}');";
            $results = $agent->prepare($sql);
            $res = $results->execute() or die("<script>window.location.href='./adds.php?ret=false&reqtype=adds&q=120'</script>");
            $ID = $_GET['pswd'];
            $analysis = new DbConnecter('../src/database/lessonsw.db');
            $sql = "SELECT * FROM inlist WHERE stid = '{$ID}';";
            $results = $analysis->prepare($sql);
            $res = $results->execute();
            $row = $res->fetchArray(SQLITE3_NUM);

            if ($row != false) {
            } else {

                $analysis->exec("CREATE TABLE l{$ID} (lesson TEXT)") or die("<script>window.location.href='../admin/adds.php?ret=false&reqtype=lesson'</script>");

                $analysis->exec("INSERT INTO inlist(stid) VALUES ('{$ID}') ") or die("<script>window.location.href='../admin/adds.php?ret=false&reqtype=lesson'</script>");
            }


            echo "<script>window.location.href='./adds.php?ret=true&reqtype=adds'</script>";
        } else if ($_GET['reqtype'] == "del") {
            $agent = new DbConnecter('../src/database/users.db');
            $sql = "DELETE FROM student WHERE ID = '{$_GET['id']}';";
            $results = $agent->prepare($sql);
            $res = $results->execute() or die("<script>window.location.href='./adds.php?ret=false&reqtype=del&q=120'</script>");

            $analysis = new DbConnecter('../src/database/lessonsw.db');
            $sql = "SELECT * FROM inlist WHERE stid = '{$_GET['id']}';";
            $results = $analysis->prepare($sql);
            $res = $results->execute();
            $row = $res->fetchArray(SQLITE3_NUM);
            if ($row != false) {
                $com = $analysis->prepare("DROP TABLE l{$_GET['id']}") or die("<script>//window.location.href='../admin/adds.php?ret=false&reqtype=del&q=121'</script>");
                $com->execute();
                $analysis->exec("DELETE FROM inlist WHERE stid = '{$_GET['id']}';") or die("<script>//window.location.href='../admin/adds.php?ret=false&reqtype=del&q=121'</script>");
            } else {
            }
            echo "<script>window.location.href='./adds.php?ret=true&reqtype=del'</script>";
        } else {
            echo "<script>window.location.href='./adds.php?ret=true&reqtype=del'</script>";
        }
    } else {
        echo "<script>window.location.href='../index.php'</script>";
    }
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
