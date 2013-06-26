<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['ADM_Username'] = NULL;
$_SESSION['ADM_UserGroup'] = NULL;
unset($_SESSION['ADM_Username']);
unset($_SESSION['ADM_UserGroup']);

if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>