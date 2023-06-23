<?php
require_once "databaseconnection.php";

class DoctorDeletion {
    private $connection;

    public function __construct() {
        $dbConnection = DatabaseConnection::getInstance();
        $this->connection = $dbConnection->getConnection();
    }

    public function deleteDoctor($doctorId) {
        // Prepare the DELETE statement
        $sql = "DELETE FROM doctor WHERE doctorId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $doctorId);

        // Execute the DELETE statement
        if (mysqli_stmt_execute($stmt)) {
            // Deletion successful
            echo "Doctor deleted successfully.";
        } else {
            // Deletion failed
            echo "Error deleting doctor: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);
    }
}

if (isset($_GET['doctorid'])) {
    $doctorid = $_GET['doctorid'];

    $doctorDeletion = new DoctorDeletion();
    $doctorDeletion->deleteDoctor($doctorid);
}
?>
