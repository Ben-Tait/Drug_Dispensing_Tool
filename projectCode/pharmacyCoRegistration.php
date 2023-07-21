<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Company Registration</title>
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

        .registration-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .registration-form input[type="text"],
        .registration-form input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .registration-form input[type="submit"] {
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

        .registration-form input[type="submit"]:hover {
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
	class PharmacyCompany{
		private $companyname;
		private $phonenumber;
		private $username;
		private $password;
		private $confirmpassword;

		public function __construct($companyname,$phonenumber,$username,$password, $confirmpassword){
			$this->companyname=$companyname;
			$this->phonenumber=$phonenumber;
			$this->username = $username;
			$this->password = $password;
			$this->confirmpassword = $confirmpassword;

		}
		public function addCompany(){
			$dbConnection = DatabaseConnection::getInstance();
			$connection = $dbConnection->getConnection();
			$errors = $this->validate($this->username,$this->password,$this->confirmpassword);
			if(count($errors)>0){
				foreach($errors as $error){
					echo $error;
				}
			}else{
			$sql = "INSERT INTO pharmco (name,phoneNumber,username,password) VALUES(?,?,?,?)";
			$stmt = mysqli_stmt_init($connection);
			$preparestmt =mysqli_stmt_prepare($stmt,$sql);
			if($preparestmt){
				$hashPassword=password_hash($this->password, PASSWORD_DEFAULT);
				mysqli_stmt_bind_param($stmt,"ssss",$this->companyname,$this->phonenumber,$this->username,$hashPassword);
				mysqli_stmt_execute($stmt);
				echo "Registration Successful!";
				mysqli_stmt_close($stmt);

			}else{
				echo "Registration Failed!";
			}
			mysqli_close($connection);
			
		}
		}
		public function validate($username,$password,$confirmpassword){
			$errors = array();
			if(strlen($password) < 8){
				array_push($errors, "Password must be atleast 8 characters!");
			}
			if($password !== $confirmpassword){
				array_push($errors, "Passwords do not match");
			}
			$dbConnection = DatabaseConnection::getInstance();
			$connection = $dbConnection->getConnection();
			$sql = "SELECT * FROM pharmco WHERE username = ?";
			$userStmt = mysqli_stmt_init($connection);
			$preparestmt = mysqli_stmt_prepare($userStmt, $sql);
			mysqli_stmt_bind_param($userStmt,"s",$username);
			mysqli_stmt_execute($userStmt);
			$result = mysqli_stmt_get_result($userStmt);
			$rows = mysqli_num_rows($result);
			if($rows>0){
				array_push($errors, "Username already exists");
			}
			mysqli_stmt_close($userStmt);
			return $errors;
		}
	}

if(isset($_POST['submit'])){
	$companyname = $_POST['company'];
	$phonenumber = $_POST['phonenumber'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirmpassword = $_POST['confirmpassword'];

$company = new PharmacyCompany($companyname,$phonenumber,$username,$password,$confirmpassword);
$company->addCompany();

}
	?>
	 <div class="registration-form">
        <h2>Company Registration</h2>
        <form action="pharmacyCoRegistration.php" method="post">
            <input type="text" name="company" placeholder="Company Name" required>
            <input type="text" name="phonenumber" placeholder="Phone Number" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
            <input type="submit" name="submit" value="Register">
        </form>

</body>
</html>