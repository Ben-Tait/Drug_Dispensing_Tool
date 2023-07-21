<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Patient Registration</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
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
		.title-container {
			margin-bottom: 20px;
			color: #337ab7;
			font-size: 24px;
			font-weight: bold;
		}

		.material-icons.icon {
			vertical-align: middle;
			font-size: 24px;
			margin-right: 10px;
		}
	</style>
</head>
<body>
	<?php
	require_once "databaseconnection.php";

	class Patient extends DatabaseConnection{
		private $firstname;
		private $lastname;
		private $ssn;
		private $address;
		private $dob;
		private $username;
		private $password;
		private $confirmpassword;
		private $gender;

		public function __construct($firstname,$lastname,$ssn,$address,$dob,$username,$password,$confirmpassword,$gender){
			$this->firstname=$firstname;
			$this->lastname=$lastname;
			$this->ssn=$ssn;
			$this->address=$address;
			$this->dob=$dob;
			$this->username=$username;
			$this->password=$password;
			$this->confirmpassword=$confirmpassword;
			$this->gender=$gender;
		}
		public function addPatient(){
			$dbConnection = DatabaseConnection::getInstance();
			$conn = $dbConnection->getConnection();
			$stmt = mysqli_stmt_init($conn);
			$sql = "INSERT INTO patient(firstName,lastName,patientSSN,address,DateOfBirth,username,password,gender) VALUES (?,?,?,?,?,?,?,?)";
			$preparestmt = mysqli_stmt_prepare($stmt,$sql);
			$hashPassword = password_hash($this->password, PASSWORD_DEFAULT);
			if($preparestmt){
				mysqli_stmt_bind_param($stmt,"ssssssss",$this->firstname,$this->lastname,$this->ssn,$this->address,$this->dob,$this->username,$hashPassword,$this->gender);
				mysqli_execute($stmt);
				echo "Registered successfully!";
			}else{
				die("Something went wrong!");
			}

		}
	}

	class addCheckedPatient extends DatabaseConnection{

		public function addCheckedPatient(){
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$ssn = $_POST['ssn'];
			$address = $_POST['address'];
			$dob = $_POST['dob'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$confirmpassword = $_POST['confirmpassword'];
			$gender = $_POST['gender'] ?? '';
			$error = $this->validate($firstname,$lastname,$ssn,$address,$dob,$username,$password,$confirmpassword,$gender);
			
			if(count($error)>0){
				foreach ($error as $e) {
					echo $e;
				}
			}else{
				$patient = new Patient($firstname,$lastname,$ssn,$address,$dob,$username,$password,$confirmpassword,$gender);
				$patient->addPatient();
			}
				
			
			

		}

		public function validate($firstname,$lastname,$ssn,$address,$dob,$username,$password,$confirmpassword,$gender){
			$errors = array();
		

			if(empty($firstname)|| empty($lastname)|| empty($ssn) || empty($address) || empty($dob) || empty($username) || empty($password) || empty($confirmpassword) || empty($gender) ){
				array_push($errors, "All fields required!");
			}
			if($password !== $confirmpassword){
				array_push($errors, "Passwords do not match");
			}
			if($password < 8){
				array_push($errors, "Password must be atleast 8 characters");
			}
			$dbConnection = DatabaseConnection::getInstance();
			$connection  = $dbConnection->getConnection();
			$stmt = mysqli_stmt_init($connection);

			// Check for duplicate SSN
			    $stmtSSN = mysqli_stmt_init($connection);
			    $sqlSSN = "SELECT * FROM patient WHERE patientSSN = ?";
			    $preparestmtSSN = mysqli_stmt_prepare($stmtSSN, $sqlSSN);
			    mysqli_stmt_bind_param($stmtSSN, "s", $ssn);
			    mysqli_stmt_execute($stmtSSN);
			    $resultSSN = mysqli_stmt_get_result($stmtSSN);
			    $rowsSSN = mysqli_num_rows($resultSSN);
			    if ($rowsSSN > 0) {
			        array_push($errors, "Social number already exists!");
			    }

			    // Check for duplicate username
			    $stmtUsername = mysqli_stmt_init($connection);
			    $sqlUsername = "SELECT * FROM patient WHERE username = ?";
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
$newPatient = new addCheckedPatient();
$newPatient->addCheckedPatient();
header("Location: patientLogin.php");
}
	?>

	<form action="patientRegistration.php" method="post">
		<div class="title-container">
		<i class="material-icons icon">person_add</i> Patient Registration <!-- Add the icon and title -->
	</div>
		<input type="text" name="firstname" placeholder="Firstname: ">
		<input type="text" name="lastname" placeholder="Lastname: ">
		<input type="text" name="ssn" placeholder="Social Security Number: ">
		<input type="text" name="address" placeholder="Address: ">
		<input type="date" name="dob" placeholder="Date of Birth: ">
		<input type="text" name="username" placeholder="Username: ">
		<input type="password" name="password" placeholder="Password: ">
		<input type="password" name="confirmpassword" placeholder="Confirm Password: ">
		<input type="radio" name="gender" value="Male" id="male">
		<label for="male">Male</label>
		<input type="radio" name="gender" value="Female" id="female">
		<label for="female">Female</label>
		<br>
		<input type="submit" name="submit" value="Register">

	</form>
</body>
</html>