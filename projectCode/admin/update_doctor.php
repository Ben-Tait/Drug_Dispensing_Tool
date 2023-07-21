
<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

class DoctorUpdater {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function updateDoctor($doctorId, $data) {
        $sql = "UPDATE doctor SET firstName = ?, lastName = ?, doctorSSN = ?, speciality = ?, startYear = ?, gender = ?, username = ? WHERE doctorId = ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $sql);

        // Extract the values from the $data array
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $doctorSSN = $data['doctorSSN'];
        $speciality = $data['speciality'];
        $startYear = $data['startYear'];
        $gender = $data['gender'];
        $username = $data['username'];

        // Bind the values to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssssss", $firstName, $lastName, $doctorSSN, $speciality, $startYear, $gender, $username, $doctorId);

        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            echo "Doctor details updated successfully.";
        } else {
            // Update failed
            echo "Error updating doctor details: " . mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
    }

    public function getCurrentDoctorDetails($doctorId) {
        $selectSql = "SELECT * FROM doctor WHERE doctorId = ?";
        $selectStmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($selectStmt, $selectSql);
        mysqli_stmt_bind_param($selectStmt, "s", $doctorId);
        mysqli_stmt_execute($selectStmt);
        $result = mysqli_stmt_get_result($selectStmt);
        $currentDoctorDetails = mysqli_fetch_assoc($result);
        mysqli_stmt_close($selectStmt);

        return $currentDoctorDetails;
    }
}

if (isset($_GET['doctorid'])) {
    $doctorId = $_GET['doctorid'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve all the updated doctor fields from the $_POST array
        $updatedData = array(
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'doctorSSN' => $_POST['doctorSSN'],
            'speciality' => $_POST['speciality'],
            'startYear' => $_POST['startYear'],
            'gender' => $_POST['gender'],
            'username' => $_POST['username']
        );

        $doctorUpdater = new DoctorUpdater($connection);
        $doctorUpdater->updateDoctor($doctorId, $updatedData);
    }

    $doctorUpdater = new DoctorUpdater($connection);
    $currentDoctorDetails = $doctorUpdater->getCurrentDoctorDetails($doctorId);

    echo "<h1>Update Doctor Details</h1>";
    echo "<form method='POST' action='update_doctor.php?doctorid=" . $doctorId . "'>";
    echo "<label for='firstName'>First Name:</label>";
    echo "<input type='text' name='firstName' id='firstName' value='" . $currentDoctorDetails['firstName'] . "'><br>";

    echo "<label for='lastName'>Last Name:</label>";
    echo "<input type='text' name='lastName' id='lastName' value='" . $currentDoctorDetails['lastName'] . "'><br>";

    echo "<label for='doctorSSN'>Doctor SSN:</label>";
    echo "<input type='text' name='doctorSSN' id='doctorSSN' value='" . $currentDoctorDetails['doctorSSN'] . "'><br>";

    echo "<label for='speciality'>Speciality:</label>";
    echo "<input type='text' name='speciality' id='speciality' value='" . $currentDoctorDetails['speciality'] . "'><br>";

    echo "<label for='startYear'>Start Year:</label>";
    echo "<input type='text' name='startYear' id='startYear' value='" . $currentDoctorDetails['startYear'] . "'><br>";

    echo "<label for='gender'>Gender:</label>";
    echo "<input type='text' name='gender' id='gender' value='" . $currentDoctorDetails['gender'] . "'><br>";

    echo "<label for='username'>Username:</label>";
    echo "<input type='text' name='username' id='username' value='" . $currentDoctorDetails['username'] . "'><br>";

    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

mysqli_close($connection);
?>
