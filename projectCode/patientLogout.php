<?php
session_start();
session_destroy();
header("Location: patientLogin.php");
?>