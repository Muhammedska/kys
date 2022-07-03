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


?>