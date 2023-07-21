<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
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

    // Get contracts with a NULL pharmacy ID
    public function getContracts()
    {
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT contractID, ContractName, contractDescription, startDate, endDate, pharmCoId FROM pharmpharmco WHERE pharmId IS NULL";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
            mysqli_stmt_execute($stmt);
        }
        $result = mysqli_stmt_get_result($stmt);
        $contracts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $contracts;
    }   

    // Get the company name of the contract
    public function getCompanyName($companyID)
    {
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT name FROM pharmco WHERE pharmCoId=?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, "s", $companyID);
            mysqli_stmt_execute($stmt);
        }
        $result = mysqli_stmt_get_result($stmt);
        $companyrow = mysqli_fetch_assoc($result);
        return $companyrow['name'];
    }
}

$contractListing = new ContractListing();
$contractsData = $contractListing->getContracts();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contracts</title>
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
        <h2>Contracts</h2>
        <?php if (!empty($contractsData)) : ?>
            <table>
                <tr>
                    <th>Pharmacy Company</th>
                    <th>Contract Name</th>
                    <th>Contract Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($contractsData as $contract) : ?>
                    <tr>
                        <td><?php echo $contractListing->getCompanyName($contract['pharmCoId']); ?></td>
                        <td><?php echo $contract['ContractName']; ?></td>
                        <td><?php echo $contract['contractDescription']; ?></td>
                        <td><?php echo $contract['startDate']; ?></td>
                        <td><?php echo $contract['endDate']; ?></td>
                        <td>
                            <a href="assign_contract.php?contractID=<?php echo $contract['contractID']; ?>">Assign</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No contracts found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
