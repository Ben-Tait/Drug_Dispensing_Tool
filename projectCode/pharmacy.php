<?php
session_start();
if(!isset($_SESSION["pharmacy"])){
	header("Location: pharmacy_login.php");
    exit;
}
 echo "Welcome" . $_SESSION['pharmacy'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<a href="pharmacy_logout.php">Logout</a>

</body>
</html>