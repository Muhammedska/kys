<?php 
session_start();
class DbConnecter extends SQLite3
{
    function __construct($path)
    {
        $this->open($path);
    }
}
$ID = "123456";
$agent = new DbConnecter('./src/database/users.db');
$sql = "SELECT * FROM student WHERE ID = '{$ID}';";
$results = $agent->prepare($sql);
$res = $results -> execute();
$row = $res->fetchArray(SQLITE3_NUM);

if($row != false){
    //echo "{$row[0]} {$row[1]} {$row[2]} {$row[3]}";
    $_SESSION['isactive'] = true;
    $_SESSION['type'] = 'student';
    $_SESSION['userid'] = $row[0];
    $_SESSION['username'] = $row[1];
    $_SESSION['graduate'] = $row[2];
    
    if($row[3] == 'peter'){
        $_SESSION['pp'] = "../assets/img/dogs/image2.jpeg";
    }else if($row[3] == 'franko'){
        $_SESSION['pp'] = "../assets/img/dogs/image3.jpeg";
    }else if($row[3] == 'ralph'){
        $_SESSION['pp'] = "../assets/img/dogs/image4.jpeg";
    }else if($row[3] == 'jessi'){
        $_SESSION['pp'] = "../assets/img/dogs/image5.jpeg";
    }else if($row[3] == 'leo'){
        $_SESSION['pp'] = "../assets/img/dogs/image6.jpeg";
    }else if($row[3] == 'mike'){
        $_SESSION['pp'] = "../assets/img/dogs/image7.jpeg";
    }
    echo "<script>window.location.href='./user/user.php'</script>";
}else{
    echo "Not Found";
}


?>