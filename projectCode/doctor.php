<?php
session_start();
if(!isset($_SESSION["user"])){
	header("Location: doctor_login.php");
}
 echo "Welcome" . $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<a href="doctor_logout.php">Logout</a>

</body>
</html>