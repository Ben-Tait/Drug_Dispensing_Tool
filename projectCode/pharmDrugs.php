<?php
session_start();
if (!isset($_SESSION["pharmacy"])) {
    header("Location: pharmacy_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Drugs</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
    <style>    
        /* General styles */
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
        .logout button {
            background-color: transparent;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 16px;
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

        /* Card styles */
        .drug-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .edit-btn, .delete-btn {
            display: inline-block;
            margin-right: 5px;
            color: #007bff;
            cursor: pointer;
        }
    </style>
    </style>

</head>
<body>
    <?php
    require_once "databaseconnection.php";

    class Drug {
        private $conn;

        public function __construct() {
            $dbConnection = DatabaseConnection::getInstance();
            $this->conn = $dbConnection->getConnection();
        }

        public function getDrug() {
            $sql = "SELECT drugName, tradeName, drugFormula, price, dateOfman, expiryDate 
        FROM drug AS d 
        WHERE d.contractID IN (SELECT contractID FROM pharmpharmco AS p WHERE p.pharmID = ?)";

            $stmt = mysqli_stmt_init($this->conn);
            $pharmid = $this->getPharmID();
            if (mysqli_stmt_prepare($stmt, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $pharmid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            $drugs = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
            return $drugs;
            }           
        }
        public function getPharmID(){
        $pharmacyusername = $_SESSION['pharmacy'];
        $stmt = mysqli_stmt_init($this->conn);
        $sql = "SELECT pharmID FROM pharmacy WHERE username = ?";
        $preparestmt = mysqli_stmt_prepare($stmt,$sql);
        if ($preparestmt) {
            mysqli_stmt_bind_param($stmt, "i", $pharmacyusername);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $pharmid = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $pharmid['pharmID'];
        }
    }
    }

    $drug = new Drug();
    $contractsData = $drug->getDrug();
    ?>
    <div class="sidebar">
        <a href="pharmacy.php"><i class="material-icons icon">home</i></a>
        <ul>
            <li><a href="pharmcontracts.php">Contracts</a></li>
            <li><a href="activeContracts.php">Contracts Inventory</a></li>
            <li><a href="pharmDrugs.php">Drugs</a></li>
            <li><a href="pharmInventory.php">Drugs Inventory</a></li>
            <li><a href="pharmPrescriptions.php">Prescriptions</a></li>
        </ul>
        <div class="logout">
            <a href="pharmacy_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
        </div>
    </div>
<div class="main-content">
    <div class="container">
        <h2 class="mt-4">Drugs</h2>
        <div class="row">
            <?php if (!empty($contractsData)) : ?>
                <?php foreach ($contractsData as $drug) : ?>
                    <div class="col-md-4">
                        <div class="drug-card p-3">
                            <p><strong><?php echo $drug['drugName']; ?></strong></p>
                            <p><?php echo $drug['drugFormula']; ?></p>
                            <p><em>Price: <?php echo $drug['price']; ?> - Expiry Date: <?php echo $drug['expiryDate']; ?></em></p>
                            <button class="btn btn-primary buy-btn" data-drug-name="<?php echo $drug['drugName']; ?>" data-drug-price="<?php echo $drug['price']; ?>" data-toggle="modal" data-target="#buyModal">Buy</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col">
                    <p>No drugs found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
    <!-- Modal -->
<div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Buy Drugs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Drug: <span id="modalDrugName"></span></p>
                <p>Price: $<span id="modalDrugPrice"></span></p>
                <form id="buyForm">
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Enter quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="newPrice">New Price:</label>
                        <input type="number" class="form-control" id="newPrice" placeholder="Enter new price" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBuy">Buy</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let selectedDrug = null;
        $('.buy-btn').on('click', function() {
            selectedDrug = {
                name: $(this).data('drug-name'),
                price: $(this).data('drug-price')
            };
            $('#modalDrugName').text(selectedDrug.name);
            $('#modalDrugPrice').text(selectedDrug.price);
            $('#quantity').val('');
            $('#newPrice').val('');
        });

        $('#confirmBuy').on('click', function() {
            const quantity = $('#quantity').val();
            const newPrice = $('#newPrice').val();
            const totalPrice = selectedDrug.price * quantity;

            if (quantity > 0 && newPrice >= 0) {
                if (confirm(`Total price: $${totalPrice}\nDo you wish to continue?`)) {
                    // Insert data into the table (You'll need to implement this part)
                    console.log('Data inserted into the table');
                    // Close the modal
                    $('#buyModal').modal('hide');
                    const form = $('<form action="insert_drug.php" method="post">' +
                        `<input type="hidden" name="drug_name" value="${selectedDrug.name}">` +
                         `<input type="hidden" name="drug_price" value="${selectedDrug.price}">` +
                        `<input type="hidden" name="quantity" value="${quantity}">` +
                        `<input type="hidden" name="new_price" value="${newPrice}">` +
                        '</form>');
                    $('body').append(form);
                    form.submit();
                }
            } else {
                alert('Please enter a valid quantity and new price.');
            }
        });
    });
</script>

</body>
</html>
