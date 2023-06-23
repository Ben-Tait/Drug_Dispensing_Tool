<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
 <form action="adminLogin.php" method="post">
 	<input type="text" name="username" placeholder="Username">
 	<input type="password" name="password" placeholder="Password">
 	<input type="submit" name="submit" value="Login">
 	
 </form>

 <?php
require_once "databaseconnection.php";

class admin{
	private $connection;
	public function __construct(){
		$conn = DatabaseConnection::getInstance();
		$this->connection = $conn->getConnection();
	}
	public function checkUser($username,$password){
		$sql="SELECT * FROM admin WHERE username = ?";
		$stmt = mysqli_stmt_init($this->connection);
		$preparestmt = mysqli_stmt_prepare($stmt,$sql);
		mysqli_stmt_bind_param($stmt,"s",$username);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$user=mysqli_fetch_array($result, MYSQLI_ASSOC);

		if($user){
			if ($user['password']===$password) {
				echo "Successful Login!";
				header("Location: admin.php");
				die();
			}
			else{
				echo "Invalid Password!";
			}
		}else{
			echo "User not Found";
		}

		mysqli_stmt_close($stmt);
	}
}
if(isset($_POST['submit'])){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$Admin = new admin();
	$Admin->checkUser($username,$password);
}

?>
</body>
</html>

