<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pharmacy Registration</title>
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
	input[type="password"],
	input[type="tel"] {
		width: 100%;
		padding: 10px;
		border: 1px solid #ccc;
		border-radius: 4px;
		margin-bottom: 10px;
		box-sizing: border-box;
	}

	label {
		margin-right: 10px;
	}

	input[type="submit"],
	input[type="button"] {
		background-color: #4267B2;
		color: #fff;
		border: none;
		padding: 10px 20px;
		border-radius: 4px;
		cursor: pointer;
		margin-top: 30px;
	}

	input[type="submit"]:hover,
	input[type="button"]:hover {
		background-color: #3b5998;
	}
</style>
</head>
<body>
	<?php
	require_once "databaseconnection.php";
    
    class Pharmacy extends DatabaseConnection {
        private $name;
        private $address;
        private $phoneNumber;
        private $username;
        private $password;
    
        public function __construct($name, $address, $phoneNumber, $username, $password) {
            $this->name = $name;
            $this->address = $address;
            $this->phoneNumber = $phoneNumber;
            $this->username = $username;
            $this->password = $password;
        }
    
        public function addPharmacy() {
            $dbConnection = DatabaseConnection::getInstance();
            $conn = $dbConnection->getConnection();
            $stmt = mysqli_stmt_init($conn);
            $sql = "INSERT INTO pharmacy (name, address, phoneNumber, username, password) VALUES (?, ?, ?, ?, ?)";
            $preparestmt = mysqli_stmt_prepare($stmt, $sql);
            $hashPassword = password_hash($this->password, PASSWORD_DEFAULT);
            if ($preparestmt) {
                mysqli_stmt_bind_param($stmt, "sssss", $this->name, $this->address, $this->phoneNumber, $this->username, $hashPassword);
                mysqli_execute($stmt);
                echo "Registered successfully!";
            } else {
                die("Something went wrong!");
            }
        }
    }
    
    class AddCheckedPharmacy extends DatabaseConnection{
        public function addCheckedPharmacy(){
            $name = $_POST['name'];
            $address = $_POST['address'];
            $phoneNumber = $_POST['phoneNumber'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];
            $error = $this->validate($name, $address, $phoneNumber, $username, $password, $confirmpassword);
            
            if(count($error) > 0){
                foreach ($error as $e) {
                    echo $e;
                }
            }else{
                $pharmacy = new Pharmacy($name, $address, $phoneNumber, $username, $password);
                $pharmacy->addPharmacy();
            }
        }
    
        public function validate($name, $address, $phoneNumber, $username, $password, $confirmpassword){
            $errors = array();
    
            if(empty($name) || empty($address) || empty($phoneNumber) || empty($username) || empty($password) || empty($confirmpassword)){
                array_push($errors, "All fields are required!");
            }
            if($password !== $confirmpassword){
                array_push($errors, "Passwords do not match");
            }
            if(strlen($password) < 8){
                array_push($errors, "Password must be at least 8 characters");
            }
    
            $dbConnection = DatabaseConnection::getInstance();
            $connection = $dbConnection->getConnection();
            $stmt = mysqli_stmt_init($connection);
    
            $stmtUsername = mysqli_stmt_init($connection);
            $sqlUsername = "SELECT * FROM pharmacy WHERE username = ?";
            $preparestmtUsername = mysqli_stmt_prepare($stmtUsername, $sqlUsername);
            mysqli_stmt_bind_param($stmtUsername, "s", $username);
            mysqli_stmt_execute($stmtUsername);
            $resultUsername = mysqli_stmt_get_result($stmtUsername);
            $rowsUsername = mysqli_num_rows($resultUsername);
            if ($rowsUsername > 0) {
                array_push($errors, "Username already exists!");
            }
    
            mysqli_stmt_close($stmtUsername);
    
            return $errors;
        }
    }
    
    if(isset($_POST['submit'])){
        $newPharmacy = new AddCheckedPharmacy();
        $newPharmacy->addCheckedPharmacy();
    }
    ?>
    
    <form action="pharmacy_registration.php" method="post">
        <input type="text" name="name" placeholder="Pharmacy Name">
        <input type="text" name="address" placeholder="Address">
        <input type="tel" name="phoneNumber" placeholder="Phone Number">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirmpassword" placeholder="Confirm Password">
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
