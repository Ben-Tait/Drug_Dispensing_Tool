<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

class PatientUpdater {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function updatePatient($patientId, $data) {
        $sql = "UPDATE patient SET firstName = ?, lastName = ?, patientSSN = ?, address = ?, gender = ?, username = ? WHERE patientId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);

        // Extract the values from the $data array
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $patientSSN = $data['patientSSN'];
        $address = $data['address'];
        $gender = $data['gender'];
        $username = $data['username'];

        // Bind the values to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssss", $firstName, $lastName, $patientSSN, $address,  $gender, $username, $patientId);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo "Patient details updated successfully.";
        } else {
            // Update failed
            echo "Error updating patient details: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
    }

    public function getCurrentPatientDetails($patientId) {
        $selectSql = "SELECT * FROM patient WHERE patientId = ?";
        $selectStmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($selectStmt, $selectSql);
        mysqli_stmt_bind_param($selectStmt, "s", $patientId);
        mysqli_stmt_execute($selectStmt);
        $result = mysqli_stmt_get_result($selectStmt);
        $currentPatientDetails = mysqli_fetch_assoc($result);
        mysqli_stmt_close($selectStmt);

        return $currentPatientDetails;
    }
}

if (isset($_GET['patientid'])) {
    $patientId = $_GET['patientid'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve all the updated patient fields from the $_POST array
        $updatedData = array(
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'patientSSN' => $_POST['patientSSN'],
            'address' => $_POST['address'],
            'gender' => $_POST['gender'],
            'username' => $_POST['username']
        );

        $patientUpdater = new PatientUpdater($connection);
        $patientUpdater->updatePatient($patientId, $updatedData);
    }

    $patientUpdater = new PatientUpdater($connection);
    $currentPatientDetails = $patientUpdater->getCurrentPatientDetails($patientId);

    echo "<h1>Update Patient Details</h1>";
    echo "<form method='POST' action='update_patient.php?patientid=" . $patientId . "'>";
    echo "<label for='firstName'>First Name:</label>";
    echo "<input type='text' name='firstName' id='firstName' value='" . $currentPatientDetails['firstName'] . "'><br>";

    echo "<label for='lastName'>Last Name:</label>";
    echo "<input type='text' name='lastName' id='lastName' value='" . $currentPatientDetails['lastName'] . "'><br>";

    echo "<label for='patientSSN'>Patient SSN:</label>";
    echo "<input type='text' name='patientSSN' id='patientSSN' value='" . $currentPatientDetails['patientSSN'] . "'><br>";

    echo "<label for='address'>Address:</label>";
    echo "<input type='text' name='address' id='address' value='" . $currentPatientDetails['address'] . "'><br>";


    echo "<label for='gender'>Gender:</label>";
    echo "<input type='text' name='gender' id='gender' value='" . $currentPatientDetails['gender'] . "'><br>";

    echo "<label for='username'>Username:</label>";
    echo "<input type='text' name='username' id='username' value='" . $currentPatientDetails['username'] . "'><br>";

    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>
