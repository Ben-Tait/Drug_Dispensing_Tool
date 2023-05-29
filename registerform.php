<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $ssn = $_POST['ssn'];
  $name = $_POST['name'];
  $address = $_POST['address'];
  $dob = $_POST['dob'];
  $nhif = $_POST['nhif'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $gender = $_POST['gender'];

  // Include the file containing the classes
  require_once('database_classes.php');

  // Create a new instance of the Patient class
  $patient = new Patient();

  // Save the patient data
  $patient->save($name, $email, $ssn, $phone, $address, $dob, $password);

  // You can add further processing or redirect the user to another page after saving the data
  // ...
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Patient Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .container {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group select {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 3px;
      border: 1px solid #ccc;
    }

    .form-group input[type="submit"] {
      background-color: #4CAF50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 3px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Patient Form</h2>
    <form action="registerform.php" method="POST">
      <div class="form-group">
        <label for="ssn">SSN:</label>
        <input type="text" id="ssn" name="ssn" required>
      </div>
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
      </div>
      <div class="form-group">
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
      </div>
      <div class="form-group">
        <label for="nhif">NHIF:</label>
        <input type="text" id="nhif" name="nhif" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required>
      </div>
      <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <input type="submit" value="Submit">
      </div>
    </form>
  </div>
</body>
</html>
