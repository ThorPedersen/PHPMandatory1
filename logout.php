<?php
session_start();
unset($_SESSION["access"]);
unset($_SESSION["username"]);
unset($_SESSION["id"]);
header("Location:login.php");
?>