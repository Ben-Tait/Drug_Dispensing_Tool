<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

class DoctorUpdater {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function updateUsername($doctorId, $newUsername) {
        $sql = "UPDATE doctor SET username = ? WHERE doctorId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newUsername, $doctorId);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo "Username updated successfully.";
        } else {
            // Update failed
            echo "Error updating username: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
    }

    public function getCurrentUsername($doctorId) {
        $selectSql = "SELECT username FROM doctor WHERE doctorId = ?";
        $selectStmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($selectStmt, $selectSql);
        mysqli_stmt_bind_param($selectStmt, "s", $doctorId);
        mysqli_stmt_execute($selectStmt);
        mysqli_stmt_bind_result($selectStmt, $currentUsername);

        mysqli_stmt_fetch($selectStmt);
        mysqli_stmt_close($selectStmt);

        return $currentUsername;
    }
}

if (isset($_GET['doctorid'])) {
    $doctorId = $_GET['doctorid'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newUsername = $_POST['username'];

        $doctorUpdater = new DoctorUpdater($connection);
        $doctorUpdater->updateUsername($doctorId, $newUsername);
    }

    $doctorUpdater = new DoctorUpdater($connection);
    $currentUsername = $doctorUpdater->getCurrentUsername($doctorId);

    echo "<h1>Update Username</h1>";
    echo "<p>Current Username: " . $currentUsername . "</p>";
    echo "<form method='POST' action='update_doctor.php?doctorid=" . $doctorId . "'>";
    echo "<label for='username'>New Username:</label>";
    echo "<input type='text' name='username' id='username'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>
