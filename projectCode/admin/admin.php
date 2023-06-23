<!DOCTYPE html>

<html>
<head></head>
<body>
<?php

class admin {
    private $connection;

    public function __construct() {
        require_once "databaseconnection.php";
        $dbConnection = DatabaseConnection::getInstance();
        $this->connection = $dbConnection->getConnection();
    }

    public function getPatients() {
        $patientsql = "SELECT patientId, firstName, lastName, patientSSN, address, DateOfBirth, username FROM patient";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $patientsql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        echo "<p>Patients</p>
        <table>
            <th>Patient ID</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>SSN</th>
            <th>Address</th>
            <th>DateofBirth</th>
            <th>Username</th>";

        while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
                    <td>" . $user['patientId'] . "</td>    
                    <td>" . $user['firstName'] . "</td>
                    <td>" . $user['lastName'] . "</td>
                    <td>" . $user['patientSSN'] . "</td>
                    <td>" . $user['address'] . "</td>
                    <td>" . $user['DateOfBirth'] . "</td>
                    <td>" . $user['username'] . "</td>
                    <td><a href='delete_patient.php?patientid=" . $user['patientId'] . "'>Delete</a></td>
                </tr>";
        }

        echo "</table>";
        mysqli_stmt_close($stmt);
    }

    public function getDoctors() {
        $doctorsql = "SELECT doctorId, firstName, lastName, doctorSSN, speciality, startYear, username FROM doctor";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $doctorsql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        echo "<p>Doctors</p>
        <table>
            <th>Doctor ID</th>    
            <th>Firstname</th>
            <th>Lastname</th>
            <th>SSN</th>
            <th>Speciality</th>
            <th>startYear</th>
            <th>Username</th>";

        while ($doctor = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
                    <td>" . $doctor['doctorId'] . "</td>
                    <td>" . $doctor['firstName'] . "</td>
                    <td>" . $doctor['lastName'] . "</td>
                    <td>" . $doctor['doctorSSN'] . "</td>
                    <td>" . $doctor['speciality'] . "</td>
                    <td>" . $doctor['startYear'] . "</td>
                    <td>" . $doctor['username'] . "</td>
                    <td><a href='delete_doctor.php?doctorid=" . $doctor['doctorId'] . "'>Delete</a></td>
                </tr>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);
    }
}

$Admin = new admin();
$Admin->getPatients();
$Admin->getDoctors();

?>
</body>
</html>
