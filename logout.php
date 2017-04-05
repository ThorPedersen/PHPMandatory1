<?php
session_start();
unset($_SESSION["access"]);
unset($_SESSION["username"]);
unset($_SESSION["id"]);
unset($_SESSION["cart"]);
session_destroy();


header("Location:login.php");
?>