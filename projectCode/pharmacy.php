<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
    exit;
}
require_once "databaseconnection.php";

class PharmacyInfo
{
    private $connection;
    private $pharmacyName;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
        $this->pharmacyName = $_SESSION["pharmacy"];
    }

    public function getPharmacyInfo()
    {
        $sql = "SELECT name, address, phoneNumber, username FROM pharmacy WHERE username = ?";
        $stmt = mysqli_stmt_init($this->connection);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $this->pharmacyName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $pharmacyInfo = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $pharmacyInfo;
        }

        return false;
    }
}

$pharmacyInfoObj = new PharmacyInfo();
$pharmacyInfo = $pharmacyInfoObj->getPharmacyInfo();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy Dashboard</title>
    <!-- Add Google Material Icons stylesheet -->
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
            background-color: #337ab7; /* Blue color */
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

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.3);
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

		
        .logout button {
			background-color: transparent;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
		}

		
		.profile-container {
			background-color: #ffffff;
			padding: 20px;
			border-radius: 4px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        <h2>Welcome, <?php echo $_SESSION['pharmacy']; ?></h2>
        <ul>
            <li><a href="pharmcontracts.php">Contracts</a></li>
            <li><a href="pharmDrugs.php">Drugs</a></li>
            <li><a href="pharmInventory.php">Drugs Inventory</a></li>
            <li><a href="pharmPrescriptions.php">Prescriptions</a></li>
        </ul>
        <div class="logout">
			<a href="pharmacy_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
		</div>
    </div>
    <div class="main-content">
		<div class="profile-container">
			<div class="user-info">
				<a href="pharmacy.php"><i class="material-icons">person</i></a>
				<h2>Welcome, <?php echo $_SESSION['pharmacy']; ?></h2>
			</div>
			<div class="profile-info">
				<label>Pharmacy Name:</label>
				<p><?php echo $pharmacyInfo['name']; ?></p>
			</div>
			<div class="profile-info">
				<label>Address:</label>
				<p><?php echo $pharmacyInfo['address']; ?></p>
			</div>			
			<div class="profile-info">
				<label>Phone Number:</label>
				<p><?php echo $pharmacyInfo['phoneNumber']; ?></p>
			</div>
			<div class="profile-info">
				<label>Username:</label>
				<p><?php echo $pharmacyInfo['username']; ?></p>
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
				<label><i class="material-icons">person</i>Pharmacy Name </label>
				<input type="text" name="firstname" placeholder="Firstname" value="<?php echo $pharmacyInfo['name']; ?>">
				<label><i class="material-icons">place</i>Pharmacy Address</label>
				<input type="text" name="lastname" placeholder="Lastname" value="<?php echo $pharmacyInfo['address']; ?>">
				<label><i class="material-icons">person</i>Username</label>
				<input type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['pharmacy']; ?>">
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
