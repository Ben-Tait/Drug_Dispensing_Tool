<?php
require_once "databaseconnection.php";
$dbConnection = DatabaseConnection::getInstance();
$connection = $dbConnection->getConnection();

// Define the number of rows to display per page
$rowsPerPage = 10;

// Check if the "page" parameter is provided in the URL
if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

// Calculate the offset for the current page
$offset = ($currentPage - 1) * $rowsPerPage;

// Retrieve the rows for the current page
$patientsql = "SELECT patientId, firstName, lastName, patientSSN, address, DateOfBirth, username FROM patient LIMIT ?, ?";
$stmt = mysqli_stmt_init($connection);
mysqli_stmt_prepare($stmt, $patientsql);
mysqli_stmt_bind_param($stmt, "ii", $offset, $rowsPerPage);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

echo "<p>Patients</p>";
echo "<table>
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
        </tr>";
}

echo "</table>";

mysqli_stmt_close($stmt);

// Calculate the total number of pages
$totalRows = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM patient"));
$totalPages = ceil($totalRows / $rowsPerPage);

// Display pagination links
echo "<div class='pagination'>";

// Generate previous page link
if ($currentPage > 1) {
    echo "<a href='?page=" . ($currentPage - 1) . "'>&laquo; Previous</a>";
}

// Generate page links
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $currentPage) {
        echo "<span class='current-page'>$i</span>";
    } else {
        echo "<a href='?page=$i'>$i</a>";
    }
}

// Generate next page link
if ($currentPage < $totalPages) {
    echo "<a href='?page=" . ($currentPage + 1) . "'>Next &raquo;</a>";
}

echo "</div>";

mysqli_close($connection);
?>
