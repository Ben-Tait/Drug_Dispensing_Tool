<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Doctor Registration</title>
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
    class Doctor extends DatabaseConnection {
        private $firstname;
        private $lastname;
        private $doctorSSN;
        private $speciality;
        private $startYear;
        private $gender;
        private $username;
        private $password;
        private $confirmpassword;
    
        public function __construct($firstname, $lastname, $doctorSSN, $speciality, $startYear, $gender, $username, $password, $confirmpassword) {
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->doctorSSN = $doctorSSN;
            $this->speciality = $speciality;
            $this->startYear = $startYear;
            $this->gender = $gender;
            $this->username = $username;
            $this->password = $password;
            $this->confirmpassword = $confirmpassword;
        }
    
        public function addDoctor() {
            $dbConnection = DatabaseConnection::getInstance();
            $conn = $dbConnection->getConnection();
            $stmt = mysqli_stmt_init($conn);
            $sql = "INSERT INTO doctor (firstName, lastName, doctorSSN, speciality, startYear, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $preparestmt = mysqli_stmt_prepare($stmt, $sql);
            $hashPassword = password_hash($this->password, PASSWORD_DEFAULT);
            if ($preparestmt) {
                mysqli_stmt_bind_param($stmt, "ssssssss", $this->firstname, $this->lastname, $this->doctorSSN, $this->speciality, $this->startYear, $this->gender, $this->username, $hashPassword);
                mysqli_execute($stmt);
                echo "Registered successfully!";
            } else {
                die("Something went wrong!");
            }
        }
    }
    
    class AddCheckedDoctor extends DatabaseConnection{
        public function addCheckedDoctor(){
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $doctorSSN = $_POST['doctorSSN'];
            $speciality = $_POST['speciality'];
            $startYear = $_POST['startYear'];
            $gender = $_POST['gender'] ?? '';
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirmpassword'];
            $error = $this->validate($firstname, $lastname, $doctorSSN, $speciality, $startYear, $gender, $username, $password, $confirmpassword);
            
            if(count($error) > 0){
                foreach ($error as $e) {
                    echo $e;
                }
            }else{
                $doctor = new Doctor($firstname, $lastname, $doctorSSN, $speciality, $startYear, $gender, $username, $password, $confirmpassword);
                $doctor->addDoctor();
            }
        }
    
        public function validate($firstname, $lastname, $doctorSSN, $speciality, $startYear, $gender, $username, $password, $confirmpassword){
            $errors = array();
    
            if(empty($firstname) || empty($lastname) || empty($doctorSSN) || empty($speciality) || empty($startYear) || empty($gender) || empty($username) || empty($password) || empty($confirmpassword)){
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
    
            
            $stmtSSN = mysqli_stmt_init($connection);
            $sqlSSN = "SELECT * FROM doctor WHERE doctorSSN = ?";
            $preparestmtSSN = mysqli_stmt_prepare($stmtSSN, $sqlSSN);
            mysqli_stmt_bind_param($stmtSSN, "s", $doctorSSN);
            mysqli_stmt_execute($stmtSSN);
            $resultSSN = mysqli_stmt_get_result($stmtSSN);
            $rowsSSN = mysqli_num_rows($resultSSN);
            if ($rowsSSN > 0) {
                array_push($errors, "Social Security Number already exists!");
            }
    
        
            $stmtUsername = mysqli_stmt_init($connection);
            $sqlUsername = "SELECT * FROM doctor WHERE username = ?";
            $preparestmtUsername = mysqli_stmt_prepare($stmtUsername, $sqlUsername);
            mysqli_stmt_bind_param($stmtUsername, "s", $username);
            mysqli_stmt_execute($stmtUsername);
            $resultUsername = mysqli_stmt_get_result($stmtUsername);
            $rowsUsername = mysqli_num_rows($resultUsername);
            if ($rowsUsername > 0) {
                array_push($errors, "Username already exists!");
            }
    
            mysqli_stmt_close($stmtSSN);
            mysqli_stmt_close($stmtUsername);
    
            return $errors;
        }
    }
    
    if(isset($_POST['submit'])){
        $newDoctor = new AddCheckedDoctor();
        $newDoctor->addCheckedDoctor();
    }
    ?>
    
    <form action="doctor_registration.php" method="post">
        <input type="text" name="firstname" placeholder="Firstname">
        <input type="text" name="lastname" placeholder="Lastname">
        <input type="text" name="doctorSSN" placeholder="Doctor's SSN">
        <input type="text" name="speciality" placeholder="Speciality">
        <input type="text" name="startYear" placeholder="Start Year">
        <input type="radio" name="gender" value="Male" id="male">
        <label for="male">Male</label>
        <input type="radio" name="gender" value="Female" id="female">
        <label for="female">Female</label>
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirmpassword" placeholder="Confirm Password">
        <input type="submit" name="submit" value="Register">
    </form>
    </body>
</html>
    


