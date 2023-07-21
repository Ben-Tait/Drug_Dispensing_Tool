<?php
session_start();
if(!isset($_SESSION["pharmacy"])){
	header("Location: pharmacy_login.php");
    exit;
}
?>
<?php
		require_once "databaseconnection.php";

class pharmacyPrescriptions{
	private $connection;

	public function __construct(){
		$conn = DatabaseConnection::getInstance();
		$this->connection = $conn->getConnection();
	}

	public function getAllPrescriptions(){
		$stmt = mysqli_stmt_init($this->connection);
		$sql = "SELECT s.username, p.prescriptionID, p.prescriptionDesc, p.prescriptiondate 
        FROM drugprescription AS p 
        JOIN patient AS s ON p.patientID = s.patientID
        WHERE p.pharmID IS NULL";
		$preparestmt = mysqli_stmt_prepare($stmt, $sql);

		if($preparestmt){
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$prescriptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $prescriptions;
		}else{
			echo "No prescriptions available!";
			return array();
		}
	}

}

// Usage example:
$pharmacyPrescriptions = new pharmacyPrescriptions();
$prescriptions = $pharmacyPrescriptions->getAllPrescriptions();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Pharmacy Prescriptions</title>
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
        .logout button {
			background-color: transparent;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
		}

        /* Table styles */
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

		.prescription-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .prescription-popup h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .prescription-popup label {
            display: block;
            margin-bottom: 8px;
        }

        .prescription-popup input,
        .prescription-popup select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .prescription-popup button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .prescription-popup button:hover {
            opacity: 0.8;
        }
	</style>
</head>
<body>
    <div class="sidebar">
        <a href="pharmacy.php"><i class="material-icons icon">home</i></a>
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
		<h2>Pharmacy Prescriptions</h2>
		<?php
		if (!empty($prescriptions)) {
			echo "<table>";
			echo "<tr>
			<th>Patientname</th>
			<th>Prescription Description</th>
			<th>Prescription Date</th>
			<th>Prescribe</th>
			</tr>";
			$isOddRow = true;
			foreach ($prescriptions as $prescription) {
				$rowClass = $isOddRow ? "odd-row" : "even-row";
				echo "<tr class='" . $rowClass . "'>";
				echo "<td>" . $prescription['username'] . "</td>";
				echo "<td>" . $prescription['prescriptionDesc'] . "</td>";
				echo "<td>" . $prescription['prescriptiondate'] . "</td>";
				echo '<td><button onclick="openPrescriptionPopup(' . $prescription['prescriptionID'] . ', \'' . $prescription['prescriptionDesc'] . '\', \'' . $prescription['prescriptiondate'] . '\')">Prescribe</button></td>';
				echo "</tr>";
				$isOddRow = !$isOddRow;
			}
			echo "</table>";
		} else {
			echo "No prescriptions available!";
		}
		?>

		<div id="prescriptionPopup" class="prescription-popup">
			<h3>Dispense Drug<i class="material-icons icon">medication</i></h3>
			<form action="insert_prescription.php" method="post">
				
				<input type="text" id="prescriptionID" name="prescriptionID" readonly hidden>
				<label for="prescriptionDesc">Prescription Description:</label>
				<input type="text" id="prescriptionDesc" name="prescriptionDesc" readonly>
				<label for="prescriptionDate">Prescription Date:</label>
				<input type="text" id="prescriptionDate" name="prescriptionDate" readonly>
				<label for="drugName">Drug Name:</label>
				<select id="drugName" name="drugName">
					<!-- Options for drug names will be added dynamically via JavaScript -->
				</select>
				<label for="quantity">Quantity:</label>
				<input type="number" id="quantity" name="quantity" min="1" required>
				<button type="submit">Prescribe</button>
			</form>
		</div>
    </div>

	<script>
		function openPrescriptionPopup(prescriptionID, prescriptionDesc, prescriptionDate) {
			document.getElementById("prescriptionID").value = prescriptionID;
			document.getElementById("prescriptionDesc").value = prescriptionDesc;
			document.getElementById("prescriptionDate").value = prescriptionDate;

			// Make an AJAX call to retrieve drug names from the server/database
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						var drugNames = JSON.parse(xhr.responseText);
						var selectElement = document.getElementById("drugName");
						selectElement.innerHTML = "";

						drugNames.forEach(function(drugName) {
							var option = document.createElement("option");
							option.text = drugName;
							option.value = drugName;
							selectElement.add(option);
						});

						document.getElementById("prescriptionPopup").style.display = "block";
					} else {
						console.error("Error retrieving drug names: " + xhr.status);
					}
				}
			};
			xhr.open("GET", "get_drug_names.php", true); // Replace "get_drug_names.php" with the actual PHP script to retrieve drug names
			xhr.send();
		}
	</script>
</body>
</html>
