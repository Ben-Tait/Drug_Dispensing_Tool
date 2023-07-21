<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
    exit;
}

class ContractAssignment
{
    private $connection;

    public function __construct()
    {
        require_once "databaseconnection.php";
        $connection = DatabaseConnection::getInstance();
        $this->connection = $connection->getConnection();
    }

    public function assignContractToPharmacy($contractID)
    {
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "UPDATE pharmpharmco SET pharmId = ? WHERE contractID = ?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
        	$pharmID = $this->getPharmID();
            mysqli_stmt_bind_param($stmt, 'ii', $pharmID, $contractID);
            mysqli_stmt_execute($stmt);
			var_dump($_SESSION['pharmacy']);}
			else{
        	echo "Something went wrong!";
        }
    }
    public function getPharmID()
    {
    	$pharmname = $_SESSION['pharmacy'];
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT pharmID FROM pharmacy WHERE username=?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, 's', $pharmname);
            mysqli_stmt_execute($stmt);
            echo "Successful";
        }
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['pharmID'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['contractID'])) {
    $contractID = $_GET['contractID'];

    // Perform the contract assignment
    $contractAssignment = new ContractAssignment();
    $contractAssignment->assignContractToPharmacy($contractID);
    header("Location: activeContracts.php");
}


