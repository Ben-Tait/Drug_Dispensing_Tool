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
	<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-form input[type="submit"] {
            background-color: #1877F2; /* Facebook blue color */
            color: #ffffff;
            width: 100%;
            padding: 12px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-form input[type="submit"]:hover {
            background-color: #0f6ad0; /* Slightly darker on hover */
        }

        .error-message {
            color: #ff0000;
            margin-top: 5px;
        }
    </style>
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
		<div class="login-form">
        <h2>Company Login</h2>
        <form action="pharmacyCoLogin.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Login">
        </form>
        <?php
        // Display error messages (if any)
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="error-message">' . $error . '</div>';
            }
        }
        ?>
    </div>
</body>
</html>