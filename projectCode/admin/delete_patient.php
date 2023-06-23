<?php
require_once "databaseconnection.php";

class PatientDeletion {
    private $connection;

    public function __construct() {
        $dbConnection = DatabaseConnection::getInstance();
        $this->connection = $dbConnection->getConnection();
    }

    public function deletePatient($patientId) {
        // Prepare the DELETE statement
        $sql = "DELETE FROM patient WHERE patientId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $patientId);

        // Execute the DELETE statement
        if (mysqli_stmt_execute($stmt)) {
            // Deletion successful
            echo "Patient deleted successfully.";
        } else {
            // Deletion failed
            echo "Error deleting patient: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);
    }
}

if (isset($_GET['patientid'])) {
    $patientid = $_GET['patientid'];

    $patientDeletion = new PatientDeletion();
    $patientDeletion->deletePatient($patientid);
}
?>
