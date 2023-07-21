<?php
session_start();
if(!isset($_SESSION["user"])){
	header("Location: doctor_login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
	<style>
		/* General styles */
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f1f1f1;
			display: flex;
		}

		/* Sidebar styles */
		.sidebar {
			background-color: #337ab7; /* Green color */
			color: #ffffff; /* White text */
			width: 200px;
			padding: 20px;
			height: 100vh; /* Full height */
		}

		/* Main content styles */
		.main-content {
			flex: 1;
			padding: 20px;
		}

		.user-info {
			margin-bottom: 20px;
		}

		.user-info h2 {
			font-size: 18px;
			margin: 0;
		}

		.navigation {
			list-style-type: none;
			padding: 0;
			margin: 0;
			margin-bottom: 20px;
		}

		.navigation li {
			margin-bottom: 10px;
		}

		.navigation li a {
			color: #ffffff;
			text-decoration: none;
		}

		.navigation li a:hover {
			text-decoration: underline;
		}

		.logout button {
			background-color: transparent;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
		}

		.logout button:hover {
			background-color: rgba(255, 255, 255, 0.3);
		}

		/* User profile styles */
		.profile-container {
			background-color: #ffffff;
			padding: 20px;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			margin-right: 20px;
		}

		.profile-info {
			margin-bottom: 20px;
		}

		.profile-info label {
			font-weight: bold;
		}

		.profile-info p {
			margin: 0;
		}

		/* Buttons styles */
		.button-container {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.button-container button {
			background-color: #4CAF50;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
			border-radius: 4px;
		}

		.button-container button.update-btn {
			background-color: #2196F3;
		}

		.button-container button.delete-btn {
			background-color: #f44336;
		}

		.button-container button:hover {
			opacity: 0.8;
		}

		/* Modal styles */
		.modal-overlay {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.7);
			z-index: 9999;
		}

		.modal-content {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background-color: #ffffff;
			padding: 30px;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			z-index: 10000;
			max-width: 400px;
		}

		.modal-close {
			position: absolute;
			top: 10px;
			right: 10px;
			cursor: pointer;
		}

		/* Icon styles */
		.material-icons {
			vertical-align: middle;
			margin-right: 10px;
		}

		/* Form styles */
		.modal-content form {
			display: block;
		}

		.modal-content form input {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border: 1px solid #ccc;
			border-radius: 4px;
			box-sizing: border-box;
		}

		.modal-content form input[type="submit"] {
			background-color: #4CAF50;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
			border-radius: 4px;
		}

		.modal-content form input[type="submit"]:hover {
			opacity: 0.8;
		}
	</style>
</head>
<body>
	<div class="sidebar">
		<div class="user-info">
			<h2>Welcome, <?php echo $_SESSION['user']; ?></h2>
		</div>
		<ul class="navigation">
			<li><a href="prescribe.php">Prescribe</a></li>
			<li><a href="prescriptionDoctor.php">Prescriptions History</a></li>
		</ul>
		<div class="logout">
			<a href="patientLogout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
		</div>
	</div>
	<?php
require_once "databaseconnection.php";
class DoctorProfile{

	private $connection;
	function __construct(){
		$this->connection = DatabaseConnection::getInstance()->getConnection();
	}
	public function getProfile(){
		$stmt = mysqli_stmt_init($this->connection);
		$sql = "SELECT * FROM doctor WHERE doctorId = ?";
		$preparestmt = mysqli_stmt_prepare($stmt, $sql);
		if($preparestmt){
			$doctorid = $this->getdoctorID();
			mysqli_stmt_bind_param($stmt, "i", $doctorid);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$profile = mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $profile;
		}
	}
	public function getdoctorID(){
    	$doctorname = $_SESSION['user'];
    	$stmt = mysqli_stmt_init($this->connection);
    	$sql = "SELECT doctorId FROM doctor WHERE username = ?";
    	$preparestmt = mysqli_stmt_prepare($stmt, $sql);
    	if($preparestmt){
    		mysqli_stmt_bind_param($stmt, "s", $doctorname);
    		mysqli_stmt_execute($stmt);
    		$result = mysqli_stmt_get_result($stmt);
    		$patient = mysqli_fetch_array($result, MYSQLI_ASSOC);
    		return $patient['doctorId'];
    	}

	}
}
$profile = new DoctorProfile();
$info = $profile->getProfile();
?>
	<div class="main-content">
		<div class="profile-container">
			<div class="user-info">
				<h2>Welcome, <?php echo $_SESSION['user']; ?></h2>
			</div>
			<div class="profile-info">
				<label>First Name:</label>
				<p><?php echo $info['firstName']; ?></p>
			</div>
			<div class="profile-info">
				<label>Last Name:</label>
				<p><?php echo $info['lastName']; ?></p>
			</div>
			<div class="profile-info">
				<label>Social Security Number:</label>
				<p><?php echo $info['doctorSSN']; ?></p>
			</div>
			<div class="profile-info">
				<label>Speciality:</label>
				<p><?php echo $info['speciality']; ?></p>
			</div>
			<div class="profile-info">
				<label>Start Year:</label>
				<p><?php echo $info['startYear']; ?></p>
			</div>
			<div class="profile-info">
				<label>Gender:</label>
				<p><?php echo $info['gender']; ?></p>
			</div>
			<div class="profile-info">
				<label>Username:</label>
				<p><?php echo $info['username']; ?></p>
			</div>
			

			<!-- Buttons container -->
			<div class="button-container">
				<button class="update-btn" onclick="showModal()"><i class="material-icons">edit</i>Update Info</button>
				<button class="delete-btn"><i class="material-icons">delete</i>Delete</button>
			</div>
		</div>
	</div>
	<div class="modal-overlay" id="modalOverlay">
		<div class="modal-content">
			<span class="modal-close" onclick="hideModal()">&times;</span>
			<form id="updateForm">
				<i class="material-icons">person</i>
				<input type="text" name="firstname" placeholder="Firstname" value="<?php echo $info['firstName']; ?>">
				<i class="material-icons">person</i>
				<input type="text" name="lastname" placeholder="Lastname" value="<?php echo $info['lastName']; ?>">
				<i class="material-icons">person</i>
				<input type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['user']; ?>">
				<input type="submit" value="Update">
			</form>
		</div>
	</div>

	<script>
		function showModal() {
			document.getElementById("modalOverlay").style.display = "block";
		}

		function hideModal() {
			document.getElementById("modalOverlay").style.display = "none";
		}
	</script>

</body>
</html>
