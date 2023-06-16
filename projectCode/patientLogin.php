<?php
session_start();
if (isset($_SESSION['user'])) {
	header("Location: patient.php");
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Patient Login</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f0f2f5;
			margin: 0;
			padding: 0;
		}

		form {
			background-color: #fff;
			padding: 20px;
			max-width: 400px;
			margin: 20px auto;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		input[type="text"],
		input[type="password"] {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
			margin-bottom: 10px;
			box-sizing: border-box;
		}

		input[type="submit"] {
			background-color: #4267B2;
			color: #fff;
			border: none;
			padding: 10px 20px;
			border-radius: 4px;
			cursor: pointer;
			margin-top: 30px;
		}

		input[type="submit"]:hover {
			background-color: #3b5998;
		}
	</style>
</head>
<body>
	<?php
	require_once "databaseconnection.php";
	class Login{
		private $username;
		private $password;

		public function __construct($username,$password){
		$this->username = $username;
		$this->password = $password;
		}

		public function checkUser(){
			$dbConnection = DatabaseConnection::getInstance();
			$conn = $dbConnection->getConnection();
			$sql = "SELECT * FROM patient WHERE username=?";
			$stmt=mysqli_stmt_init($conn);
			$preparestmt = mysqli_stmt_prepare($stmt, $sql);
			mysqli_stmt_bind_param($stmt,"s",$this->username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$user= mysqli_fetch_array($result, MYSQLI_ASSOC);


			if($user){
				if(password_verify($this->password, $user['password'])){
					echo "Successful Login";
					session_start();
					$_SESSION['user'] = $this->username;
					header("Location: patient.php");
					die();
				}else{
					echo "Invalid Password!";
				}
			}else{
				echo "User not found!";
			}
			mysqli_stmt_close($stmt);

		}
			
	}
	if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$login = new Login($username,$password);
	$login->checkUser();
}

	?>
	<form action="patientLogin.php" method="post">
		<input type="text" name="username" placeholder="Username: ">
		<input type="password" name="password" placeholder="Password: ">
		<input type="submit" name="submit" value="Login">
	</form>


</body>
</html>