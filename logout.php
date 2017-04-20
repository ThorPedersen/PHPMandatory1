<?php
session_start();

//unsets all sessions after logout, and destroys session
unset($_SESSION["access"]);
unset($_SESSION["username"]);
unset($_SESSION["id"]);
unset($_SESSION["cart"]);
unset($_SESSION["price"]);
session_destroy();

header("Location:login.php");
?>