<?php
session_start();
if (!isset($_SESSION['company'])) {
    header("Location: pharmacyCoLogin.php");
    exit;
}

require_once "databaseconnection.php";

class ContractListing
{
    private $connection;

    public function __construct()
    {
        $connection = DatabaseConnection::getInstance();
        $this->connection = $connection->getConnection();
    }

    public function getContracts($contractname,$contractdescription,$startdate,$enddate)
    {
    	$pharmcoid = $this->getCompanyID();
    	$sql="INSERT INTO pharmpharmco(ContractName,contractDescription,startDate,endDate,pharmCoId) VALUES (?,?,?,?,?)";
    	$stmt = mysqli_stmt_init($this->connection);
    	$preparestmt=mysqli_stmt_prepare($stmt,$sql);
    	if($preparestmt){
    		mysqli_stmt_bind_param($stmt,"sssss",$contractname,$contractdescription,$startdate,$enddate,$pharmcoid);
    		mysqli_stmt_execute($stmt);

    	}
    }

    public function getCompanyID()
    {
    	$stmt = mysqli_stmt_init($this->connection);
        $companyname=$_SESSION['company'];
        $sql="SELECT pharmCoId FROM pharmco WHERE username=?";
        $preparestmt=mysqli_stmt_prepare($stmt,$sql);
        if($preparestmt){
        	mysqli_stmt_bind_param($stmt,'s',$companyname);
        	mysqli_stmt_execute($stmt);
        }
        $result = mysqli_stmt_get_result($stmt);
        $company = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($company) {
                return $company['pharmCoId'];
            } else {
                die("Company not found.");
            }
    }
    public function getCompanyContracts()
    {
        $pharmcoid = $this->getCompanyID();
        $sql = "SELECT * FROM pharmpharmco WHERE pharmCoId=?";
        $stmt = mysqli_stmt_init($this->connection);
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, "s", $pharmcoid);
            mysqli_stmt_execute($stmt);
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $contracts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        return $contracts;
    }
}

$contractListing = new ContractListing();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission and contract creation
    $contractname = $_POST['contractname'];
    $contractdescription = $_POST['contractdesc'];
    $startdate = $_POST['startDate'];
    $enddate = $_POST['endDate'];
    $contractListing->getContracts($contractname, $contractdescription, $startdate, $enddate);
}
$contracts = $contractListing->getCompanyContracts();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contracts</title>
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

        /* Form styles */
        form {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 50px;
            background-color: #fff;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #337ab7;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #26537a;
        }
        form input[name="contractdesc"] {
            height: 60px;


    </style>
</head>
<body>
    <div class="sidebar">
        <a href="company.php"><i class="material-icons icon">home</i></a>
        <ul>
            <li><a href="drugCompany.php">Add Drugs</a></li>
            <li><a href="drugTable.php">Drugs Inventory</a></li>
            <li><a href="contracts.php">Contracts</a></li>
            <li><a href="contractsTable.php">Contracts Inventory</a></li>
        </ul>
        <div class="logout">
            <a href="pharmacy_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
        </div>
    </div>
    
    <div class="main-content">
        <h2>Contracts</h2>
        <form method="POST" action="contracts.php">
            <input type="text" name="contractname" placeholder="Contract Name: ">
            <input type="text" name="contractdesc" placeholder="Contract Description: ">
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate">
            <label for="endDate">End Date</label>
            <input type="date" name="endDate">
            <input type="submit" name="submit" value="New Contract">
            
        </form>
        
    </div>
        
</body>
</html>
