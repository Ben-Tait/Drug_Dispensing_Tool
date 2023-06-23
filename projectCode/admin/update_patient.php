<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

if (isset($_GET['patientid'])) {
    $patientid = $_GET['patientid'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the new username value from the form submission
        $newUsername = $_POST['username'];

        // Prepare and execute the SQL UPDATE statement
        $sql = "UPDATE patient SET username = ? WHERE patientId = ?";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newUsername, $patientid);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo "Username updated successfully.";
        } else {
            // Update failed
            echo "Error updating username: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
    }

    // Retrieve the current username of the patient
    $selectSql = "SELECT username FROM patient WHERE patientId = ?";
    $selectStmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($selectStmt, $selectSql);
    mysqli_stmt_bind_param($selectStmt, "s", $patientid);
    mysqli_stmt_execute($selectStmt);
    mysqli_stmt_bind_result($selectStmt, $currentUsername);

    mysqli_stmt_fetch($selectStmt);
    mysqli_stmt_close($selectStmt);

    // Display the form to update the username
    echo "<h1>Update Username</h1>";
    echo "<p>Current Username: " . $currentUsername . "</p>";
    echo "<form method='POST' action='update_patient.php?patientid=" . $patientid . "'>";
    echo "<label for='username'>New Username:</label>";
    echo "<input type='text' name='username' id='username'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>
