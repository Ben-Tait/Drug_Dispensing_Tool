<?php
session_start();
if(!isset($_SESSION["pharmacy"])){
    header("Location: pharmacy_login.php");
    exit;
}
?>

<?php
require_once "databaseconnection.php";

class Prescription
{
    private $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    public function insertPrescription($prescriptionID, $prescriptionDesc, $prescriptionDate, $drugName, $quantity)
    {
        // Prepare the SQL statement to insert data into the table
        $sql = "INSERT INTO patientPrescriptions (quantity, drugId, TotalCost, prescriptionId, prescriptionDate) VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement and bind parameters to prevent SQL injection
        $drugId = $this->getDrugID($drugName);
        $sellingprice = $this->getPrice($drugName);
        $totalcost = $sellingprice * $quantity;
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iiiis", $quantity, $drugId, $totalcost, $prescriptionID, $prescriptionDate);

        // Execute the statement
        if ($stmt->execute()) {
            // Success! Data inserted into the table
            $this->addPharmID($prescriptionID);

            return true;
        } else {
            // Error occurred
            echo "Error: " . $sql . "<br>" . $this->connection->error;
            return false;
        }

        // Close the statement (Note: The connection should be closed outside this method)
        $stmt->close();
    }
    public function getDrugID($drugName){
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT drug_ID FROM drug WHERE drugName = ?";
        $preparestmt =mysqli_stmt_prepare($stmt, $sql);
        if($preparestmt){
            mysqli_stmt_bind_param($stmt, "s", $drugName);
            mysqli_stmt_execute($stmt);
            $result=  mysqli_stmt_get_result($stmt);
            $drug = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $drug['drug_ID'];
        }
    }
    public function getPrice($drugName){
        $drugid = $this->getDrugID($drugName);
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "SELECT sellingprice FROM pharmdrug WHERE drugId = ?";
        $preparestmt =mysqli_stmt_prepare($stmt, $sql);
        if($preparestmt){
            mysqli_stmt_bind_param($stmt, "i", $drugid);
            mysqli_stmt_execute($stmt);
            $result=  mysqli_stmt_get_result($stmt);
            $drug = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $drug['sellingprice'];
        }
    }
    public function addPharmID($prescriptionID){
        $pharmid = $this->getPharmID();
        $stmt = mysqli_stmt_init($this->connection);
        $sql = "UPDATE drugprescription SET pharmID = ? WHERE prescriptionId = ?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if($preparestmt){
            mysqli_stmt_bind_param($stmt, "ii", $pharmid,$prescriptionID);
            mysqli_stmt_execute($stmt);
        }
    }    

    public function getPharmID(){
            $pharmname = $_SESSION['pharmacy'];
            $stmt = mysqli_stmt_init($this->connection);
            $sql="SELECT pharmID FROM pharmacy WHERE username=?";
            $preparestmt=mysqli_stmt_prepare($stmt,$sql);
            if($preparestmt){
                mysqli_stmt_bind_param($stmt,'s',$pharmname);
                mysqli_stmt_execute($stmt);
            }
            $result = mysqli_stmt_get_result($stmt);
            $company = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($company) {
                return $company['pharmID'];
            } else {
                die("Company not found.");
            }

    }


    }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get values from the form
    $prescriptionID = $_POST["prescriptionID"];
    $prescriptionDesc = $_POST["prescriptionDesc"];
    $prescriptionDate = $_POST["prescriptionDate"];
    $drugName = $_POST["drugName"];
    $quantity = $_POST["quantity"];
$prescription = new Prescription();
$prescription->insertPrescription($prescriptionID,$prescriptionDesc,$prescriptionDate, $drugName,$quantity);
}
?>
