<?php
session_start();
setcookie ("sans-gluten", $_SESSION["id"], time()-3600, "/");
session_unset();
session_destroy();
header("Location: index.php");
exit;



