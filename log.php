<?php
session_start();
$_SESSION['loggedin'] = "YES";
header('Location: index.php');
?>