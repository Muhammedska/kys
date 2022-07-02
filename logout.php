<?php
session_start();
session_destroy();
session_reset();
setcookie('sessionisopened', 'false', 0, "/");
setcookie('type', '', 0, "/");
setcookie('userid', '', 0, "/");
setcookie('username', '', 0, "/");
setcookie('graduate', '', 0, "/");
setcookie('pp', '', 0, "/");

echo "<script>window.location.href='index.php'</script>";
