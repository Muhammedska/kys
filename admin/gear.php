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
            if ($_GET['type'] == 'st') {
                $agent = new DbConnecter('../src/database/users.db');
                $sql = "INSERT INTO student (ID, name, grade, pp) VALUES ('{$_GET['pswd']}', '{$_GET['uname']}', '{$_GET['studentstatus']}', '{$_GET['pp']}');";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./adds.php?ret=false&reqtype=adds&q=120'</script>");

                //$ID = $_GET['pswd'];
                //$analysis = new DbConnecter('../src/database/lessonsw.db');
                //$sql = "SELECT * FROM inlist WHERE stid = '{$ID}';";
                //$results = $analysis->prepare($sql);
                //$res = $results->execute();
                //$row = $res->fetchArray(SQLITE3_NUM);
                //
                //if ($row != false) {
                //} else {
                //
                //    $analysis->exec("CREATE TABLE l{$ID} (lesson TEXT)") or die("<script>window.location.href='../admin/adds.php?ret=false&reqtype=lesson'</script>");
                //
                //    $analysis->exec("INSERT INTO inlist(stid) VALUES ('{$ID}') ") or die("<script>window.location.href='../admin/adds.php?ret=false&reqtype=lesson'</script>");
                // }
                echo "<script>window.location.href='./adds.php?ret=true&reqtype=adds'</script>";
            } else if ($_GET['type'] == "tea") {
                $agent = new DbConnecter('../src/database/users.db');
                $sql = "INSERT INTO teacher (ID, name, lesson, pp) VALUES ('{$_GET['pswd']}', '{$_GET['uname']}', '{$_GET['teachersubject']}', '{$_GET['pp']}');";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./addt.php?ret=false&reqtype=adds&q=120'</script>");
                echo "<script>window.location.href='./addt.php?ret=true&reqtype=addt'</script>";
            }
        } else if ($_GET['reqtype'] == "del") {
            if ($_GET['type'] == 'st') {
                $agent = new DbConnecter('../src/database/users.db');
                $sql = "DELETE FROM student WHERE ID = '{$_GET['id']}';";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./adds.php?ret=false&reqtype=del&q=120'</script>");

                $sql = "DELETE FROM statstudent WHERE ID = '{$_GET['id']}';";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./adds.php?ret=false&reqtype=del&q=120'</script>");

                //$analysis = new DbConnecter('../src/database/lessonsw.db');
                //$sql = "SELECT * FROM inlist WHERE stid = '{$_GET['id']}';";
                //$results = $analysis->prepare($sql);
                //$res = $results->execute();
                //$row = $res->fetchArray(SQLITE3_NUM);
                //if ($row != false) {
                //    $analysis->busyTimeout(3000);
                //    $com = $analysis->prepare("DROP TABLE l{$_GET['id']}") or die("<script>//window.location.href='../admin/adds.php?ret=false&reqtype=del&q=121'</script>");
                //    $com->execute();
                //    
                //    $analysis->exec("DELETE FROM inlist WHERE stid = '{$_GET['id']}';") or die("<script>//window.location.href='../admin/adds.php?ret=false&reqtype=del&q=121'</script>");
                //} else {
                //}
                echo "<script>window.location.href='./adds.php?ret=true&reqtype=del'</script>";
            } else if ($_GET['type'] == "tea") {
                $agent = new DbConnecter('../src/database/users.db');

                $sql = "DELETE FROM teacher WHERE ID = '{$_GET['id']}';";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./addt.php?ret=false&reqtype=del&q=120'</script>");

                $sql = "DELETE FROM statsteacher WHERE teacher = '{$_GET['id']}';";
                $results = $agent->prepare($sql);
                $res = $results->execute() or die("<script>window.location.href='./addt.php?ret=false&reqtype=del&q=120'</script>");
                echo "<script>window.location.href='./addt.php?ret=true&reqtype=del'</script>";
            }
        } else if ($_GET['reqtype'] == "notify") {
            if ($_GET['type'] == 'del') {
                $analysis = new DbConnecter('../src/database/users.db');
                $sql = "DELETE FROM `notify` WHERE `mass` = '{$_GET['mass']}' AND `notify` = '{$_GET['text']}' AND `sender` = '{$_GET['sender']}';";
                $results = $analysis->prepare($sql);
                $res = $results->execute();
                echo "<script>window.location.href='../admin/admin.php?ret=true&reqtype=delreq'</script>";
            } else if ($_GET['type'] == 'add') {
                $analysis = new DbConnecter('../src/database/users.db');
                $sql = "INSERT INTO `notify` (`mass`, `notify`, `sender`) VALUES ('{$_GET['mass']}', '" . str_replace("'", '"', $_GET['notifytext']) . "', 'kurum');";
                $results = $analysis->prepare($sql);
                $res = $results->execute();
                echo "<script>window.location.href='../admin/admin.php?ret=true&reqtype=addnotify'</script>";
            }
        } else if ($_GET['reqtype'] == "video") {
            if ($_GET['type'] == 'perm') {
                $apth = $_GET['path'];
                $myfile = fopen($apth, "w") or die("Unable to open file!");
                if ($_GET['perm'] == '1') {
                    fwrite($myfile, "1");
                    echo "<script>window.location.href='../admin/exams.php?ret=true&reqtype=openvideo'</script>";
                } elseif ($_GET['perm'] == '0') {
                    fwrite($myfile, "0");
                    echo "<script>window.location.href='../admin/exams.php?ret=true&reqtype=closevideo'</script>";
                }
            } else if ($_GET['type'] == 'create') {
                $folder = '../src/video/exams/';
                $examname = $_GET['examname'];
                mkdir($folder . $examname, 0777, true);
                mkdir($folder . $examname . '/biyoloji', 0777, true);
                mkdir($folder . $examname . '/fizik', 0777, true);
                mkdir($folder . $examname . '/kimya', 0777, true);
                mkdir($folder . $examname . '/matematik', 0777, true);
                $secfile = fopen($folder . $examname . "/active.txt", "w");
                fwrite($secfile, "0");
                fclose($secfile);
                $createdb = fopen($folder . $examname . "/info.db", "w");
                $infoDB = new DbConnecter($folder . $examname . '/info.db');
                $lessons = ['biyoloji', 'fizik', 'kimya', 'matematik'];
                for ($i = 0; $i < count($lessons); $i++) {
                    $infoDB->exec("CREATE TABLE `{$lessons[$i]}` (`videoname` TEXT, `watcher` TEXT, `date` TEXT);");
                }
                echo "<script>window.location.href='../admin/exams.php?ret=true&reqtype=createvideo'</script>";
            } else if ($_GET['type'] == 'delete') {
                $dirname = $_GET['path'];
                $folder_path = str_replace('active.txt', '', $dirname);
                $files = glob($folder_path . '/*');

                // Deleting all the files in the list
                foreach ($files as $file) {

                    if (is_file($file))

                        // Delete the given file
                        unlink($file);
                }
                $folders = scandir($folder_path);
                foreach ($folders as $folder) {
                    if ($folder != '.' && $folder != '..') {
                        $folder_path = $folder_path . '/' . $folder;
                        $files = glob($folder_path . '/*');
                        foreach ($files as $file) {
                            if (is_file($file))
                                unlink($file);
                        }
                        rmdir($folder_path);
                    }
                }
                rmdir($folder_path);
                echo "<script>window.location.href='../admin/exams.php?ret=true&reqtype=deldir'</script>";
            }
        }else if($_GET['reqtype'] == 'app'){
            if($_GET['var'] == 'name'){
                $agent = new DbConnecter('../src/database/users.db');                
                $sql = "UPDATE `app` SET `val`='{$_GET['value']}' WHERE var = 'name' ;";
                $results = $agent->prepare($sql);
                $res = $results->execute();

                echo "<script>window.location.href='../admin/admin.php?ret=true&reqtype=app'</script>";
            }else if($_GET['var'] == 'carousel'){
                $agent = new DbConnecter('../src/database/users.db');                
                $sql = "UPDATE `app` SET `val`='{$_GET['key']}' WHERE var = 'carousel' ;";
                $results = $agent->prepare($sql);
                $res = $results->execute();

                echo "<script>window.location.href='../admin/admin.php?ret=true&reqtype=app'</script>";
            }else if($_GET['var'] == 'notify'){
                $agent = new DbConnecter('../src/database/users.db');                
                $sql = "UPDATE `app` SET `val`='{$_GET['key']}' WHERE var = 'notify' ;";
                $results = $agent->prepare($sql);
                $res = $results->execute();

                echo "<script>window.location.href='../admin/admin.php?ret=true&reqtype=app'</script>";
            }

        }else {
            echo "<script>window.location.href='./admin.php'</script>";
        }
    } else {
        echo "<script>window.location.href='../index.php'</script>";
    }
} else {
    echo "<script>window.location.href='../index.php'</script>";
}
