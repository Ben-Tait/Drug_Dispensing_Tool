
<?php
session_start();
if (!isset($_SESSION['company'])) {
	header("Location: pharmacyCoLogin.php");
}
echo "Welcome" . $_SESSION['company'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<a href="companyLogout.php">Logout</a>
</body>
</html>