

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Registration</title>
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
	input[type="date"] {
		width: 100%;
		padding: 10px;
		border: 1px solid #ccc;
		border-radius: 4px;
		margin-bottom: 10px;
		box-sizing: border-box;
	}

	input[type="radio"] {
		margin-right: 5px;
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
    class Admin{
        private $username;
        private $password;
        private $confirmpassword;
    
        public function __construct($username, $password, $confirmpassword) {
            
            $this->username = $username;
            $this->password = $password;
            $this->confirmpassword = $confirmpassword;
        }
    
        public function addAdmin() {
    $dbConnection = DatabaseConnection::getInstance();
    $conn = $dbConnection->getConnection();
    $stmt = mysqli_stmt_init($conn);
    $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $preparestmt = mysqli_stmt_prepare($stmt, $sql);
    $hashPassword = password_hash($this->password, PASSWORD_DEFAULT);

    if ($preparestmt) {
        mysqli_stmt_bind_param($stmt, "ss", $this->username, $hashPassword);
        mysqli_execute($stmt);
        echo "Registered successfully!";
    } else {
        die("Something went wrong!");
    }
}

    }
   
    
    if(isset($_POST['submit'])){
    	$username = $_POST['username'];
    	$password = $_POST['password'];
    	$confirmpassword = $_POST['confirmpassword'];
        $newAdmin= new Admin($username, $password, $confirmpassword);
        $newAdmin->addAdmin();
    }
    ?>
    
    <form action="adminRegistration.php" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirmpassword" placeholder="Confirm Password">
        <input type="submit" name="submit" value="Register">
    </form>
    </body>
</html>
    


