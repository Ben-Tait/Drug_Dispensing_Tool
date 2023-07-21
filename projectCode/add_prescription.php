<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: doctor_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "databaseconnection.php";

    $prescriptionDesc = $_POST['prescription'];
    $prescriptiondate = $_POST['prescriptionDate'];
    $patientId = $_POST['patientId'];
    $doctorname = $_SESSION['user'];

    class Prescription {
        private $connection;

        public function __construct(){
            $connection = DatabaseConnection::getInstance();
            $this->connection = $connection->getConnection();
        }

        public function addDrugPrescription($prescriptionDesc, $prescriptiondate, $patientId, $doctorname) {
            $stmt = mysqli_stmt_init($this->connection);
            $doctorId = $this->getDoctorID($doctorname);
            $sql = "INSERT INTO drugprescription (prescriptionDesc, prescriptiondate, patientId, doctorId) VALUES (?, ?, ?, ?)";
            $preparestmt = mysqli_stmt_prepare($stmt, $sql);
            if ($preparestmt) {
                mysqli_stmt_bind_param($stmt, "ssii", $prescriptionDesc, $prescriptiondate, $patientId, $doctorId);
                mysqli_stmt_execute($stmt);
                return true;
            } else {
                return false;
            }
        }

        public function getDoctorID($doctorname) {
            $stmt = mysqli_stmt_init($this->connection);
            $sql = "SELECT doctorId FROM doctor WHERE username = ?";
            $preparestmt = mysqli_stmt_prepare($stmt, $sql);
            if ($preparestmt) {
                mysqli_stmt_bind_param($stmt, "s", $doctorname);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $doctor = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $doctor['doctorId'];
            }
        }
    }

    // Create a new Prescription object and add the prescription
    $prescription = new Prescription();
    $success = $prescription->addDrugPrescription($prescriptionDesc, $prescriptiondate, $patientId, $doctorname);

    if ($success) {
        header("Location: prescriptionDoctor.php"); // Redirect to a success page
        exit;
    } else {
        echo "Error adding prescription. Please try again.";
    }
}
