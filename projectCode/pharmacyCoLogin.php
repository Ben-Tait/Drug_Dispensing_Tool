<?php
session_start();
if (isset($_SESSION['company'])) {
	header("Location: ");
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Company Login</title>
</head>
<body>
	<?php
	require_once "databaseconnection.php";
	class CompanyLogin{
		private $username;
		private $password;

		public function __construct($username,$password){
			$this->username = $username;
			$this->password = $password;
		}
		public function checkCompany(){
			$dbConnection = DatabaseConnection::getInstance();
			$connection = $dbConnection->getConnection();
			$sql = "SELECT * FROM pharmco WHERE username=?";
			$stmt = mysqli_stmt_init($connection);
			$preparestmt = mysqli_stmt_prepare($stmt,$sql);
			mysqli_stmt_bind_param($stmt,"s",$this->username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$company = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($company){
				if(password_verify($this->password, $company['password'])){
					session_start();
					$_SESSION['company'] = $this->username;
					header("Location: company.php");
					echo "Successful Login";
					die();
				}else{
					echo "Invalid Password!";
				}
			}
			else{
				echo "User not found!";
				}
			mysqli_stmt_close($stmt);
			mysqli_close($connection);
		}
	}
if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];

	$company = new CompanyLogin($username,$password);
	$company->checkCompany();
}
	?>
		<form action="pharmacyCoLogin.php" method="post">
			<input type="text" name="username" placeholder="Username: ">
			<input type="password" name="password" placeholder="Password: " >
			<input type="submit" name="submit" value="Login">			
		</form>
</body>
</html>