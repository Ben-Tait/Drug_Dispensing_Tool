<html>
  <body>
    <form method="GET" action="search.php">
  <input type="text" name="search_query" placeholder="Search by patient username">
  <input type="submit" name="submit" value="Search">
</form>
    <?php
       
if(isset($_GET['submit'])){
  $connection = new mysqli("localhost", "root", "", "drugproject");

// Check connection
if ($connection->connect_error){
    echo "Failed to connect to MySQL: " . $connection->connect_error;
    exit();
}

// Get the search query from the form submission

if (!empty($_GET['search_query'])) {
  $searchQuery = $_GET['search_query'];
  $query = "SELECT * FROM patient WHERE username LIKE ?";
  $statement = mysqli_stmt_init($connection);

// Prepare the statement
  $preparestatement = mysqli_stmt_prepare($statement, $query);
  if ($preparestatement) {
    // Bind the search query to the prepared statement
  $searchParam = "%" . $searchQuery . "%";
  mysqli_stmt_bind_param($statement, "s", $searchParam);
  mysqli_stmt_execute($statement);
  }
// Get the result
  $result = mysqli_stmt_get_result($statement);
        if (mysqli_num_rows($result)>0) {
          // code...  
            while ($row = mysqli_fetch_assoc($result)) {
              echo "Patient ID: " . $row['patientId'] . "<br>";
              echo "Username: " . $row['username'] . "<br>";
              echo "<hr>";
            }
          }
        else{
            echo "user not found";
          }
    mysqli_stmt_close($statement);
}else{
  echo "Enter a search!";
}



// Prepare the SQL query with a placeholder for the search query


// Close the statement and the database connection
mysqli_close($connection);

}



?>

  </body>
</html>
