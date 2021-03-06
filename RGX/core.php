<?php
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
$datax = [];
$IDS = '[';
$NS = '[';
while ($row = $results->fetchArray()) {
    $datax += [$row['ID'] => array($row['ID'], $row['name'], $row['grade'], $row['pp'])];
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
    $datat += [$row['ID'] => array($row['ID'], $row['name'], $row['lesson'], $row['pp'])];
    array_push($datam, array($row['ID'], $row['name'], $row['lesson'], $row['pp']));
};

$sql = "SELECT * FROM app WHERE var='token'";
$results = $db->prepare($sql);
$res = $results->execute();
$row = $res->fetchArray(SQLITE3_NUM);
$token = $row[1];

if ($_GET['token'] == $token) {
    if ($_GET['type'] == 'add') {
        if ($_GET['t'] == 'student') {
            $sql = "INSERT INTO student (ID, name, grade, pp) VALUES ('" . $_GET['id'] . "', '" . $_GET['name'] . "', '" . $_GET['grade'] . "', '" . 'franko' . "')";
            $db->exec($sql);
            echo 'success';
        } else if ($_GET['t'] == 'teacher') {
            $sql = "INSERT INTO teacher (ID, name, lesson, pp) VALUES ('" . $_GET['id'] . "', '" . $_GET['name'] . "', '" . $_GET['lesson'] . "', '" . 'franko' . "')";
            $db->exec($sql);            
            echo 'success';
        } else {
            echo 'error';
        }
    } else if ($_GET['type'] == 'getlog') {
        $sql = "SELECT * FROM log";
        $results = $db->query($sql);
        //$results = $res->execute();
        $notify = [];
        while ($row = $results->fetchArray()) {
            array_push($notify, array($row['mission'], $row['logtm'], $row['user']));
        };
        $con = 1;
        if (count($notify) == 0) {
            echo "kay??t g??nl?????? bo??";
        } else {
            foreach (array_reverse($notify) as $key => $value) {
                echo $con . " | " . $value[0] . " | " . $value[1] . " | " . $value[2] . "<br>";
                $con++;
            }
        }
    }else if($_GET['type'] == 'clearstudent' ){
        $sql = "DELETE FROM student";
        $db->exec($sql) or die('????renci listesi silinirken hata olu??tu');
        $sql = "DELETE FROM statsstudent";
        $db->exec($sql) or die('????renci istatistik listesi silinirken hata olu??tu');
        $sql = "DELETE FROM teacherreq";
        $db->exec($sql) or die('????retmen istek listesi silinirken hata olu??tu');
        echo 'success';

    }else if ($_GET['type'] == 'sendnotify') {
        $sql = "INSERT INTO `notify` (`mass`, `notify`, `sender`) VALUES ('{$_GET['mass']}', '" . str_replace("'", '"', $_GET['notifytext']) . "', 'kurum');";
       $db->exec($sql);
        
    } else if ($_GET['type'] == 'istudent') {
        $ID = $_GET['id'];
        $sql = "SELECT * FROM student WHERE ID = '{$ID}';";
        $results = $db->prepare($sql);
        $res = $results->execute();
        $row = $res->fetchArray(SQLITE3_NUM);

        if ($row != false) {
            $lessons = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"];
            $sql = "SELECT * FROM teacherreq WHERE stid = '{$ID}';";
            $results = $db->prepare($sql);
            $res = $results->execute();
            //$row = $res->fetchArray(SQLITE3_NUM);
            //var_dump($row);
            $waitlist = [];
            $q = '';
            while ($row = $res->fetchArray()) {
                //var_dump($row);    
                array_push($waitlist, $row[2]);
                $b = $row[2];
                $q .=  $b. ',';
            }
            if (strlen($q) > 0) {
                $q = substr($q, 0, -1);
                echo $q;
            } else {
                echo "0";
            }
        } else {
            echo "error";
        }
    } else if($_GET['type'] == 'reqadd'){
        $ID = $_GET['id'];
        $tarih = date("Y-m-d h:i:sa");
        $sql = "SELECT * FROM student WHERE ID = '{$ID}';";
        $results = $db->prepare($sql);
        $res = $results->execute();
        $row = $res->fetchArray(SQLITE3_NUM);
        $name = $row[1];
        $grade = $row[2];
        $db->exec("INSERT INTO statsstudent (ID, subject) VALUES ('{$ID}','{$_GET['lesson']}') ");
        $db->exec("INSERT INTO teacherreq(sname,stid,subject,graduate) VALUES ('{$name}','{$ID}','{$_GET['lesson']}','{$grade}')") ;
        $sql = "INSERT INTO `log` (`mission`, `logtm`,`user`) VALUES ('{$name} - {$_GET['lesson']} randevu talebi olu??turma tamamland?? | WALLE DESKTOP APP', '{$tarih}','{$name}');";        
        $db->exec($sql);
        echo "success";    
    }

}else if(empty($_GET['token'])){
    echo "error";
}else{
}
