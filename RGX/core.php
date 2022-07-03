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
            $results = $db->prepare($sql);
            $res = $results->execute();
            echo 'success';
        } else if ($_GET['t'] == 'teacher') {
            $sql = "INSERT INTO teacher (ID, name, lesson, pp) VALUES ('" . $_GET['id'] . "', '" . $_GET['name'] . "', '" . $_GET['lesson'] . "', '" . 'franko' . "')";
            $results = $db->prepare($sql);
            $res = $results->execute();
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
            echo "kayıt günlüğü boş";
        } else {
            foreach (array_reverse($notify) as $key => $value) {
                echo $con . " | " . $value[0] . " | " . $value[1] . " | " . $value[2] . "<br>";
                $con++;
            }
        }
    } else if ($_GET['type'] == 'sendnotify') {
        $sql = "INSERT INTO `notify` (`mass`, `notify`, `sender`) VALUES ('{$_GET['mass']}', '" . str_replace("'", '"', $_GET['notifytext']) . "', 'kurum');";
        $results = $db->prepare($sql);
        $res = $results->execute();
    }
}
