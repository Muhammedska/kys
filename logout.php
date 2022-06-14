<?php 
session_start();
session_destroy();
session_reset();
echo "<script>window.location.href='index.php'</script>";
?>