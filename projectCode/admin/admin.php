<?php

class admin {
    private $connection;
    private $limit = 10; // Number of records per page

    public function __construct() {
        require_once "databaseconnection.php";
        $dbConnection = DatabaseConnection::getInstance();
        $this->connection = $dbConnection->getConnection();
    }

    public function getPatients($page = 1) {
        $offset = ($page - 1) * $this->limit;
        $countQuery = "SELECT COUNT(*) as total FROM patient";
        $countResult = mysqli_query($this->connection, $countQuery);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalRecords = $countRow['total'];
        $totalPages = ceil($totalRecords / $this->limit);

        $patientsql = "SELECT patientId, firstName, lastName, patientSSN, address, DateOfBirth, username FROM patient LIMIT ?, ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $patientsql);
        mysqli_stmt_bind_param($stmt, 'ii', $offset, $this->limit);
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
            <th>Username</th>
            <th>Action</th>";

        while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
                    <td>" . $user['patientId'] . "</td>    
                    <td>" . $user['firstName'] . "</td>
                    <td>" . $user['lastName'] . "</td>
                    <td>" . $user['patientSSN'] . "</td>
                    <td>" . $user['address'] . "</td>
                    <td>" . $user['DateOfBirth'] . "</td>
                    <td>" . $user['username'] . "</td>
                    <td>
                    <a href='delete_patient.php?patientid=" . $user['patientId'] . "'>Delete</a>
                    <a href='update_patient.php?patientid=" . $user['patientId'] . "'>Update</a>
                    </td>
                </tr>";
        }

        echo "</table>";

        // Pagination links
        echo "<div>";
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
            }
        }
        echo "</div>";

        mysqli_stmt_close($stmt);
    }

    public function getDoctors($page = 1) {
        $offset = ($page - 1) * $this->limit;
        $countQuery = "SELECT COUNT(*) as total FROM doctor";
        $countResult = mysqli_query($this->connection, $countQuery);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalRecords = $countRow['total'];
        $totalPages = ceil($totalRecords / $this->limit);

        $doctorsql = "SELECT doctorId, firstName, lastName, doctorSSN, speciality, startYear, username FROM doctor LIMIT ?, ?";
        $stmt = mysqli_stmt_init($this->connection);
        mysqli_stmt_prepare($stmt, $doctorsql);
        mysqli_stmt_bind_param($stmt, 'ii', $offset, $this->limit);
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
            <th>Username</th>
            <th>Action</th>";

        while ($doctor = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
                    <td>" . $doctor['doctorId'] . "</td>
                    <td>" . $doctor['firstName'] . "</td>
                    <td>" . $doctor['lastName'] . "</td>
                    <td>" . $doctor['doctorSSN'] . "</td>
                    <td>" . $doctor['speciality'] . "</td>
                    <td>" . $doctor['startYear'] . "</td>
                    <td>" . $doctor['username'] . "</td>
                    <td><a href='delete_doctor.php?doctorid=" . $doctor['doctorId'] . "'>Delete</a>
						<a href='update_doctor.php?doctorid=" . $doctor['doctorId'] . "'>Update</a>
                    </td>
                </tr>";
        }

        // Pagination links
        echo "<div>";
        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
            }
        }
        echo "</div>";

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);
    }
}

$Admin = new admin();
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$Admin->getPatients($page);
$Admin->getDoctors($page);

?>
