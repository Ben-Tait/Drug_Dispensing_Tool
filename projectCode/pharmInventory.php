<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
    exit;
}
require_once "databaseconnection.php";
class Drugs{
	private $connection;
	function __construct(){
		$this->connection = DatabaseConnection::getInstance()->getConnection();
	}
	public function getDrugs(){
		$stmt = mysqli_stmt_init($this->connection);
		$sql = "SELECT d.drugName, p.quantity, p.buyingprice, p.sellingprice 
        FROM drug AS d 
        JOIN pharmdrug AS p ON d.drug_ID = p.drugId 
        WHERE p.pharmID = ?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        $pharmid = $this->getPharmID();
        if ($preparestmt) {
        	mysqli_stmt_bind_param($stmt, "i", $pharmid);
        	mysqli_stmt_execute($stmt);
        	$result = mysqli_stmt_get_result($stmt);
        	$drugs = mysqli_fetch_all($result, MYSQLI_ASSOC);
        	return $drugs;
        }
	}
	public function getPharmID(){
        $pharmacyusername = $_SESSION['pharmacy'];
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT pharmID FROM pharmacy WHERE username = ?";
        $preparestmt = mysqli_stmt_prepare($stmt,$sql);
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, "i", $pharmacyusername);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $pharmid = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $pharmid['pharmID'];
        }
    }
}
$drug = new Drugs();
$drugsData = $drug->getDrugs();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Drug Inventory</title>
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
		}
		.logout button {
			background-color: transparent;
			color: #ffffff;
			border: none;
			padding: 8px 16px;
			cursor: pointer;
			font-size: 16px;
		}
		 table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
		</style>
</head>
<body>	
	<div class="sidebar">
        <a href="pharmacy.php"><i class="material-icons icon">home</i></a>
        <ul>
            <li><a href="pharmcontracts.php">Contracts</a></li>
            <li><a href="activeContracts.php">Contracts Inventory</a></li>
            <li><a href="pharmDrugs.php">Drugs</a></li>
            <li><a href="pharmInventory.php">Drugs Inventory</a></li>
            <li><a href="pharmPrescriptions.php">Prescriptions</a></li>
        </ul>
        <div class="logout">
			<a href="pharmacy_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
		</div>
	</div>
		<div class="main-content">
        <h1>Drug Table</h1>
        <div class="main-container">
            <table>
                <tr>
                    <th>Drug Name</th>
                    <th>Quantity (in stock)</th>
                    <th>Buying Price (per Unit)</th>
                    <th>Selling Price(per Unit)</th>
                    
                </tr>
                <?php foreach ($drugsData as $drugData) : ?>
                    <tr>
                        <td><?php echo $drugData['drugName']; ?></td>
                        <td><?php echo $drugData['quantity']; ?></td>
                        <td><?php echo $drugData['buyingprice']; ?></td>
                        <td><?php echo $drugData['sellingprice']; ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
    </div>


</body>
</html>