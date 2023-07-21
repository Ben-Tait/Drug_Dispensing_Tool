<?php
session_start();
if(!isset($_SESSION["user"])){
	header("Location: doctor_login.php");
}
?>


<?php
require_once "databaseconnection.php";
class DoctorPrescriptions{
	private $connection;
	function __construct(){
		$this->connection = DatabaseConnection::getInstance()->getConnection();
	}
	public function getAllPrescriptions(){
    $stmt = mysqli_stmt_init($this->connection);
    $sql = "SELECT prescriptionDesc, prescriptiondate FROM drugprescription WHERE doctorId = ?";
    $preparestmt = mysqli_stmt_prepare($stmt, $sql);
    $patientid = $this->getpatientID();
    if($preparestmt){
        mysqli_stmt_bind_param($stmt, "i", $patientid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $prescriptions = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch as associative array
        mysqli_stmt_close($stmt); // Close the statement
        return $prescriptions;
    }
}

    public function getpatientID(){
    	$doctorname = $_SESSION['user'];
    	$stmt = mysqli_stmt_init($this->connection);
    	$sql = "SELECT doctorId FROM doctor WHERE username = ?";
    	$preparestmt = mysqli_stmt_prepare($stmt, $sql);
    	if($preparestmt){
    		mysqli_stmt_bind_param($stmt, "s", $doctorname);
    		mysqli_stmt_execute($stmt);
    		$result = mysqli_stmt_get_result($stmt);
    		$doctor = mysqli_fetch_array($result, MYSQLI_ASSOC);
    		return $doctor['doctorId'];
    	}

	}
}
$prescriptions = new DoctorPrescriptions();
$pres = $prescriptions->getAllPrescriptions();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
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
		.content {
		margin: 20px;
		width: 75%;
	}

	.content h1 {
		margin-bottom: 20px;
	}

	table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
	<div class="sidebar">
		<div class="user-info">
			<a href="doctor.php"><i class="material-icons">home</i></a>
		</div>
		<ul class="navigation">

			<li><a href="prescribe.php">Prescribe</a></li>
			<li><a href="prescriptionDoctor.php">Prescription History</a></li>
		</ul>
		<div class="logout">
			<a href="patientLogout.php"><button><i class="material-icons">logout</i>Log Out</button></a>
		</div>
	</div>
   <div class="content">
		<h1>Transaction History</h1>
		<table>
			<tr>
				<th>Prescription Description</th>
				<th>Prescription Date</th>
				
			</tr>
			<?php
			if (isset($pres) && !empty($pres)) {
				foreach ($pres as $prescription) {
					echo "<tr>";
					echo "<td>" . $prescription["prescriptionDesc"] . "</td>";
					echo "<td>" . $prescription["prescriptiondate"] . "</td>";
					echo "</tr>";
				}
			} else {
				echo "<tr><td colspan='4'>No Prescriptions found.</td></tr>";
			}
			?>
		</table>
	</div>

</body>
</html>