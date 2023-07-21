<?php
require_once "databaseconnection.php";

// Assuming you have a function to get the database connection
$connection = DatabaseConnection::getInstance()->getConnection();

// Fetch the values from the database using a prepared statement
$sql = "SELECT ContractName FROM pharmpharmco";
$stmt = mysqli_stmt_init($connection);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Store the values in an array
    $categoryOptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dropdown Example</title>
</head>
<body>
    <h2>Select a Category:</h2>
    <form action="process_form.php" method="POST">
        <label for="category">Choose a category:</label>
        <select name="category" id="category">
            <?php foreach ($categoryOptions as $category) : ?>
                <option value="<?php echo htmlspecialchars($category['ContractName']); ?>"><?php echo htmlspecialchars($category['ContractName']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
