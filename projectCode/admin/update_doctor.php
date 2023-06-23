<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

if (isset($_GET['doctorid'])) {
    $doctorid = $_GET['doctorid'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the new username value from the form submission
        $newUsername = $_POST['username'];

        // Prepare and execute the SQL UPDATE statement
        $sql = "UPDATE doctor SET username = ? WHERE doctorId = ?";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $newUsername, $doctorid);

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
    $selectSql = "SELECT username FROM doctor WHERE doctorId = ?";
    $selectStmt = mysqli_stmt_init($connection);
    mysqli_stmt_prepare($selectStmt, $selectSql);
    mysqli_stmt_bind_param($selectStmt, "s", $doctorid);
    mysqli_stmt_execute($selectStmt);
    mysqli_stmt_bind_result($selectStmt, $currentUsername);

    mysqli_stmt_fetch($selectStmt);
    mysqli_stmt_close($selectStmt);

    // Display the form to update the username
    echo "<h1>Update Username</h1>";
    echo "<p>Current Username: " . $currentUsername . "</p>";
    echo "<form method='POST' action='update_doctor.php?doctorid=" . $doctorid . "'>";
    echo "<label for='username'>New Username:</label>";
    echo "<input type='text' name='username' id='username'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>