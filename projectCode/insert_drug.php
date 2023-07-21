<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
    exit;
}
?>
<?php
require_once "databaseconnection.php";

class DrugHandler {
    private $conn;

    public function __construct() {
        $dbConnection = DatabaseConnection::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

    public function insertDrugData($drugName, $quantity, $oldprice, $newPrice) {
        $sql = "INSERT INTO pharmdrug (quantity,buyingprice,sellingprice, drugId, pharmID) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($this->conn);
        $drugId = $this->getDrugID($drugName);
        $pharmid = $this->getPharmID();


        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "iiiii", $quantity, $oldprice, $newPrice,$drugId, $pharmid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        } else {
            return false;
        }
    }
    public function getDrugID($drugName){
        $sql = "SELECT drug_ID FROM drug WHERE drugName = ?";
        $stmt = mysqli_stmt_init($this->conn);
        $preparestmt = mysqli_stmt_prepare($stmt,$sql);
        if($preparestmt){
            mysqli_stmt_bind_param($stmt, "s", $drugName);
            mysqli_stmt_execute($stmt);
        }
        $result = mysqli_stmt_get_result($stmt);
        $drugid = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $drugid['drug_ID'];
    }
    public function getPharmID(){
        $pharmacyusername = $_SESSION['pharmacy'];
        $stmt = mysqli_stmt_init($this->conn);
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
    

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $drugName = $_POST["drug_name"];
    $drugPrice = $_POST["drug_price"];
    $quantity = (int)$_POST["quantity"];
    $newPrice = (float)$_POST["new_price"];

    // Create an instance of the DrugHandler class
    $drugHandler = new DrugHandler();

    // Insert data into the table
    if ($drugHandler->insertDrugData($drugName, $quantity, $drugPrice, $newPrice)) {
        // Redirect back to the drug page or display a success message
        echo "Bought Successfully!";
        header("Location: pharmInventory.php");
        exit();
    } else {
        // Redirect back to the drug page or display an error message
        echo "Problem! Try Again!";
        var_dump($drugHandler->insertDrugData($drugName, $quantity, $drugPrice, $newPrice));
        header("Location: pharmInventory.php");
        exit();
    }
}

