<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

class PatientUpdater {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function updateUsername($patientId, $newUsername) {
        $sql = "UPDATE patient SET username = ? WHERE patientId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newUsername, $patientId);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo "Username updated successfully.";
        } else {
            // Update failed
            echo "Error updating username: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
    }

    public function getCurrentUsername($patientId) {
        $selectSql = "SELECT username FROM patient WHERE patientId = ?";
        $selectStmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($selectStmt, $selectSql);
        mysqli_stmt_bind_param($selectStmt, "s", $patientId);
        mysqli_stmt_execute($selectStmt);
        mysqli_stmt_bind_result($selectStmt, $currentUsername);

        mysqli_stmt_fetch($selectStmt);
        mysqli_stmt_close($selectStmt);

        return $currentUsername;
    }
}

if (isset($_GET['patientid'])) {
    $patientId = $_GET['patientid'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newUsername = $_POST['username'];

        $patientUpdater = new PatientUpdater($connection);
        $patientUpdater->updateUsername($patientId, $newUsername);
    }

    $patientUpdater = new PatientUpdater($connection);
    $currentUsername = $patientUpdater->getCurrentUsername($patientId);

    echo "<h1>Update Username</h1>";
    echo "<p>Current Username: " . $currentUsername . "</p>";
    echo "<form method='POST' action='update_patient.php?patientid=" . $patientId . "'>";
    echo "<label for='username'>New Username:</label>";
    echo "<input type='text' name='username' id='username'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>
