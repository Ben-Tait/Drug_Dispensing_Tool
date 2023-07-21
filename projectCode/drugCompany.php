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

    public function insertData($drugName, $tradeName, $drugFormula, $price, $dateOfMan, $expiryDate, $category) {
    $pharmCoId = $this->getCompanyID();
    $contractId = $this->getContractID($category);

    $sql = "INSERT INTO drug (drugName, tradeName, drugFormula, price, dateOfman, expiryDate, pharmCoId, contractID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($this->conn);
    $errors = $this->checks($drugName, $tradeName, $drugFormula, $price, $dateOfMan, $expiryDate);
    if ($errors) {
        foreach ($errors as $error) {
        echo "$error";
    }
    }else{

            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssdsssi", $drugName, $tradeName, $drugFormula, $price, $dateOfMan, $expiryDate, $pharmCoId, $contractId);
                $success = mysqli_stmt_execute($stmt);

                if ($success) {
                    echo "Data inserted successfully.";
                } else {
                    echo "Error inserting data: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing statement: " . mysqli_stmt_error($stmt);
    }
    $this->getDrug();
}
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

    public function checks($drugName, $tradeName, $drugFormula, $price, $dateOfMan, $expiryDate){
        $errors = array();
        if (empty($drugName)||empty($tradeName)||empty($drugFormula)||empty($price)||empty($dateOfMan)||empty($expiryDate)) {
            array_push($errors, "All Fields Required");
        }
        $manDate = new DateTime($dateOfMan);
        $currentDate = new Datetime();
        $expiry = new Datetime($expiryDate);

        if ($manDate > $currentDate) {
            array_push($errors, "Date of manufacture cannot be greater than todays date");
        }
        if($expiry < $manDate){
            array_push($errors, "Date of Expiry cannot be less than todays date");
        }
        return $errors;

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
                </tr>";
        }

        echo "</table>";
            }else{
                echo "No drugs added!";
            }
        mysqli_stmt_close($stmt);
    }
    
    public function getContracts(){
        $sql = "SELECT ContractName FROM pharmpharmco WHERE pharmCoId=?";
        $companyid = $this->getCompanyID();
        $stmt = mysqli_stmt_init($this->conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $companyid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $categoryOptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $categoryOptions;
        }
    }
    public function getContractID($category){
        $stmt = mysqli_stmt_init($this->conn);
        $sql = "SELECT contractID FROM pharmpharmco WHERE ContractName = ?";
        $preparestmt = mysqli_stmt_prepare($stmt, $sql);
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, "s", $category);
            mysqli_stmt_execute($stmt);
        }
        $result = mysqli_stmt_get_result($stmt);
        $contract = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $contract['contractID'];

    }

    }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            $drugName = $_POST['drugName'];
            $tradeName = $_POST['tradeName'];
            $drugFormula = $_POST['drugFormula'];
            $price = $_POST['price'];
            $dateOfMan = $_POST['dateOfMan'];
            $expiryDate = $_POST['expiryDate'];
            $category = $_POST['category'];

            $drug = new Drug();
            $contract=$drug->insertData($drugName, $tradeName, $drugFormula, $price, $dateOfMan, $expiryDate, $category);
            header("Location: drugTable.php");
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drug</title>
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

        /* Form styles */
        form {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            background-color: #fff;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #337ab7;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #26537a;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Link styles */
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #337ab7; /* Blue color */
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
        <form action="drugCompany.php" method="post">
            <div>
                <i class="material-icons">drug</i>
                <input type="text" name="drugName" placeholder="Drug Name" required>
            </div>

            <div>
                <i class="material-icons">local_pharmacy</i>
                <input type="text" name="tradeName" placeholder="Trade Name" required>
            </div>

            <div>
                <i class="material-icons">description</i>
                <input type="text" name="drugFormula" placeholder="Drug Formula" required>
            </div>

            <div>
                <i class="material-icons">attach_money</i>
                <input type="number" name="price" placeholder="Drug Price" required>
            </div>

            <div>
                <i class="material-icons">calendar_today</i>
                <input type="date" name="dateOfMan" required>
            </div>

            <div>
                <i class="material-icons">event_busy</i>
                <input type="date" name="expiryDate" required>
            </div>

            <div>
                <i class="material-icons">category</i>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php 
                    $drug = new Drug();
                    $categoryOptions = $drug->getContracts();
                    foreach ($categoryOptions as $category) : 
                    ?>
                        <option value="<?php echo htmlspecialchars($category['ContractName']); ?>"><?php echo htmlspecialchars($category['ContractName']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <input type="submit" name="submit" value="Add">
            </div>
        </form>
        
    </div>
   
</body>
</html>




