<?php
// get_drug_names.php

require_once "databaseconnection.php";
$conn = DatabaseConnection::getInstance()->getConnection();

$stmt = mysqli_stmt_init($conn);
$sql = "SELECT drugName FROM drug";
$preparestmt = mysqli_stmt_prepare($stmt, $sql);

if ($preparestmt) {
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$drugs = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// Send the drug names as a JSON response
	header("Content-Type: application/json");
	echo json_encode(array_column($drugs, 'drugName'));
} else {
	// Handle error
	http_response_code(500);
	echo json_encode(array("error" => "Failed to fetch drug names."));
}