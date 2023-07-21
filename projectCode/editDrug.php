<?php
session_start();
if (!isset($_SESSION['company'])) {
    header("Location: companyLogout.php");
    exit;
}

require_once "databaseconnection.php";

class EditDrug {
    private $conn;

    public function __construct() {
        $dbConnection = DatabaseConnection::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

    public function getDrugDetails($drugName) {
        $sql = "SELECT drugName, tradeName, drugFormula FROM drug WHERE pharmCoId = ? AND drugName = ?";
        $stmt = mysqli_stmt_init($this->conn);
        $companyId = $this->getCompanyID();
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "is", $companyId, $drugName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $drug = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);

            return $drug;
        }
    }

    public function updateDrugDetails($drugName, $tradeName, $drugFormula) {
        $sql = "UPDATE drug SET tradeName = ?, drugFormula = ? WHERE pharmCoId = ? AND drugName = ?";
        $stmt = mysqli_stmt_init($this->conn);
        $companyId = $this->getCompanyID();
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssis", $tradeName, $drugFormula, $companyId, $drugName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            // Redirect back to the drugTable.php after successful update
            header("Location: drugTable.php");
            exit;
        }
    }

    public function getCompanyID() {
        // ... (Your existing implementation to get the company ID)
    }
}

$editDrug = new EditDrug();

if (isset($_GET['drugName'])) {
    $drugName = $_GET['drugName'];
    $drugDetails = $editDrug->getDrugDetails($drugName);
    
    if ($drugDetails) {
        // Populate the form with the drug details
        $tradeName = $drugDetails['tradeName'];
        $drugFormula = $drugDetails['drugFormula'];
    } else {
        // Redirect to drugTable.php if drug details not found
        header("Location: drugTable.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission and update the database
    if (isset($_POST['edit-drug-name'], $_POST['edit-trade-name'],$_POST['edit-drug-formula'])) {
            $editedDrugName = $_POST['edit-drug-name'];
            $editedTradeName = $_POST['edit-trade-name'];
            $editedDrugFormula = $_POST['edit-drug-formula'];
    // Update the database with the edited drug details
            $editDrug->updateDrugDetails($editedDrugName, $editedTradeName, $editedDrugFormula);
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- ... (your other head elements) ... -->
</head>
<body>
    <h1>Edit Drug Details</h1>
    <form method="post">
        <label for="edit-drug-name">Drug Name:</label>
        <input type="text" id="edit-drug-name" name="edit-drug-name" value="<?php echo isset($drugName) ? htmlspecialchars($drugName) : ''; ?>" readonly>
        <label for="edit-trade-name">Trade Name:</label>
    <input type="text" id="edit-trade-name" name="edit-trade-name" value="<?php echo isset($tradeName) ? htmlspecialchars($tradeName) : ''; ?>">

    <label for="edit-drug-formula">Drug Formula:</label>
    <input type="text" id="edit-drug-formula" name="edit-drug-formula" value="<?php echo isset($drugFormula) ? htmlspecialchars($drugFormula) : ''; ?>">

    <button type="submit">Update</button>
</form>
</body>
</html>

