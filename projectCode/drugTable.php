<?php
session_start();
if (!isset($_SESSION['company'])) {
    header("Location: companyLogout.php");
    exit;
}

require_once "databaseconnection.php";

class Drug {
    private $conn;

    public function __construct() {
        $dbConnection = DatabaseConnection::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

      public function getDrug() {
        $sql = "SELECT drugName, tradeName, drugFormula, price, dateOfman, expiryDate FROM drug WHERE pharmCoId = ?";
        $stmt = mysqli_stmt_init($this->conn);
        $companyid = $this->getCompanyID();
        if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt,"i",$companyid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
            }
        if(mysqli_num_rows($result)>0){

        echo "<p>Drugs</p>
        <table>
            <th>Name</th>
            <th>Trade Name</th>
            <th>Drug Formula</th>
            <th>price</th>
            <th>Manufacture Date</th>
            <th>Expiry Date</th>
            ";
            

        while ($drug= mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "<tr>
                    <td>" . $drug['drugName'] . "</td>    
                    <td>" . $drug['tradeName'] . "</td>
                    <td>" . $drug['drugFormula'] . "</td>
                    <td>" . $drug['price'] . "</td>
                    <td>" . $drug['dateOfman'] . "</td>
                    <td>" . $drug['expiryDate'] . "</td>
                    <td>
                    <a href='editDrug.php?drugName=" . $drug['drugName'] . "' ><button class='action-btn edit-btn')><i class='material-icons'>edit</i> Edit</button></a>
                    <button class='action-btn delete-btn'><i class='material-icons'>delete</i> Delete</button>
                </td>
                </tr>";
        }

        echo "</table>";
            }else{
                echo "No drugs added!";
            }
        mysqli_stmt_close($stmt);
    }
    public function getCompanyID() {
        $name = $_SESSION['company'];
        $sql = "SELECT pharmCoId FROM pharmco WHERE username = ?";
        $stmt = mysqli_stmt_init($this->conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $company = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($company) {
                return $company['pharmCoId'];
            } else {
                die("Company not found.");
            }

            mysqli_stmt_close($stmt);
        } else {
            die("Error preparing statement: " . mysqli_stmt_error($stmt));
        }
    }
}

$drug = new Drug();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drug Table</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
            display: flex;
        }

        /* Sidebar styles */
        .sidebar {
            background-color: #337ab7; /* Blue color */
            color: #ffffff; /* White text */
            width: 200px;
            padding: 20px;
            height: 100vh; /* Full height */
        }

        /* Main content styles */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .logout button {
            background-color: transparent;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 16px;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    /* Action buttons */
    .action-btn {
        display: inline-block;
        padding: 5px 10px;
        margin-right: 5px;
        cursor: pointer;
        color: #fff;
        border: none;
        border-radius: 3px;
    }

    .edit-btn {
        background-color: #4CAF50;
    }

    .delete-btn {
        background-color: #f44336;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="company.php"><i class="material-icons icon">home</i></a>
        <ul>
            <li><a href="drugCompany.php">Add Drugs</a></li>
            <li><a href="drugTable.php">Drugs Inventory</a></li>
            <li><a href="contracts.php">Contracts</a></li>
            <li><a href="contractsTable.php">Contracts Inventory</a></li>
        </ul>
        <div class="logout">
            <a href="pharmacy_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
        </div>
    </div>
    <div class="main-content">
        <h1>Drug Table</h1>
        <?php
        // Output the drug table
        $drug->getDrug();
        ?>
    </div>
    <script>
    function editDrug(drugName) {
        window.location.href = 'editDrug.php?drugName=' + encodeURIComponent(drugName);
    }
</script
</body>
</html>
