<?php
session_start();
setcookie ("Sans-gluten", $_SESSION["id"], time()-1);
session_unset();
session_destroy();
header("Location: index.php");
exit;



