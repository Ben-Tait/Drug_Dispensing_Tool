<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: doctor_login.php");
}

require_once "databaseconnection.php";

class PrimaryPhysician
{
    private $connection;

    public function __construct()
    {
        $connection = DatabaseConnection::getInstance();
        $this->connection = $connection->getConnection();
    }

    public function getDoctors($searchTerm)
    {
         $sql = "SELECT * FROM doctor WHERE firstName LIKE ? OR lastName LIKE ? OR speciality LIKE ?";
            $stmt = mysqli_stmt_init($this->connection);
            $preparestmt=mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $searchTerm,$searchTerm,$searchTerm);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Name</th><th>Last Name</th><th>Specialty</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["firstName"] . "</td>";
                echo "<td>" . $row["lastName"] . "</td>";
                echo "<td>" . $row["speciality"] . "</td>";
                echo "<td><a class='contact-button'href='contact.php?doctor_id=" . $row["doctorId"] . "'>Contact</a></td>";
                echo "<td><a class='action-button' href='contact.php?doctor_id=" . $row["doctorId"] . "'>Primary Physician</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No doctors found.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Primary Physician</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        .search-container input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        /* Add this CSS code to your existing <style> block */

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

/* Add this CSS code to your existing <style> block */

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

.action-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.action-button:hover {
    background-color: #45a049;
}

.contact-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #337ab7;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.contact-button:hover {
    background-color: #286090;
}


        .no-results {
            color: #888;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Primary Physician</h1>

    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by name or specialty">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    if (isset($_GET["search"])) {
        $searchTerm = $_GET["search"];
        $primaryPhysician = new PrimaryPhysician();
        $primaryPhysician->getDoctors($searchTerm);
    }
    ?>
</div>
</body>
</html>
