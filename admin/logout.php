<?php 
require "../config.php";
//logout

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>