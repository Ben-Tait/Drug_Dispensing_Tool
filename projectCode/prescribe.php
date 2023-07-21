<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: doctor_login.php");
    exit;
}
?>

<html>
  <head>
    <title>Doctor Prescriptions</title>
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
            background-color: #337ab7; /* Green color */
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

        .user-info {
            margin-bottom: 20px;
        }

        .user-info h2 {
            font-size: 18px;
            margin: 0;
        }

        .navigation {
            list-style-type: none;
            padding: 0;
            margin: 0;
            margin-bottom: 20px;
        }

        .navigation li {
            margin-bottom: 10px;
        }

        .navigation li a {
            color: #ffffff;
            text-decoration: none;
        }

        .navigation li a:hover {
            text-decoration: underline;
        }

        .logout button {
            background-color: transparent;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout button:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Search field styles */
        .search-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-container button {
            background-color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            margin-left: 5px;
            cursor: pointer;
        }

        /* Search icon styles */
        .material-icons.search-icon {
            font-size: 24px;
            color: #337ab7;
        }

        /* Prescribe button styles */
        .prescribe-button {
            background-color: #4CAF50; /* Green background */
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .prescribe-button:hover {
            opacity: 0.8;
        }

        /* Icon styles */
        .material-icons {
            vertical-align: middle;
            margin-right: 10px;
        }
        ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
}

.username {
    flex: 1;
}

.prescribe-button {
    background-color: #4CAF50; /* Green background */
    color: #ffffff;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 4px;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #ffff;
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 10000;
    max-width: 400px;
}

.modal-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

.modal-field {
    margin-bottom: 30px;
}

.modal-field label {
    display: block;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 25px;
}

.modal-field input[type="text"],
.modal-field textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.modal-buttons button {
    background-color: #4CAF50;
    color: #ffffff;
    border: none;
    padding: 10px 16px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 4px;
    margin-left: 10px;
}

.modal-buttons button.cancel-button {
    background-color: #ccc;
    color: #333;
}

/* Media query for responsiveness */
@media screen and (max-width: 600px) {
    .modal-content {
        max-width: 90%;
    }
}

    </style>
  </head>
  <body>
     <div class="sidebar">
        <div class="user-info">
            <a href="patient.php"><i class="material-icons">home</i></a>
        </div>
        <ul class="navigation">
            <li><a href="prescribe.php">Prescribe</a></li>
            <li><a href="prescriptionDoctor.php">Prescriptions History</a></li>
        </ul>
        <div class="logout">
            <a href="doctor_logout.php"><button><i class="material-icons icon">logout</i>Log Out</button></a>
        </div>
    </div>
    <div class="main-content">
        <form method="GET" action="prescribe.php">
        <div class="search-container">
            <input type="text" name="search_query" placeholder="Search by patient username">
            <button type="submit" name="submit"><i class="material-icons search-icon">search</i></button>
        </div>
    </form>

    <?php
    require_once "databaseconnection.php";
    class Prescription {
      private $connection;

      public function __construct(){
        $connection = DatabaseConnection::getInstance();
        $this->connection = $connection->getConnection();
      }

      public function getPatient($searchQuery) {
        $query = "SELECT * FROM patient WHERE username LIKE ?";
        $statement = mysqli_stmt_init($this->connection);

        // Prepare the statement
        $preparestatement = mysqli_stmt_prepare($statement, $query);
        if ($preparestatement) {
          // Bind the search query to the prepared statement
          $searchParam = "%" . $searchQuery . "%";
          mysqli_stmt_bind_param($statement, "s", $searchParam);
          mysqli_stmt_execute($statement);
          // Get the result
          $result = mysqli_stmt_get_result($statement);
          if (mysqli_num_rows($result) > 0) {
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>";
    echo "<span class='username'>Username: " . $row['username'] . "</span>";
    echo "<button class='prescribe-button' data-patient-id='" . $row['patientId'] . "' data-username='" . $row['username'] . "'>Prescribe</button>";
    echo "</li>";
}
            echo "</ul>";
          } else {
            echo "User not found";
          }
          mysqli_stmt_close($statement);
        }
      }
    }

    if (isset($_GET['submit'])) {
      $prescription = new Prescription();
      $searchQuery = $_GET['search_query'] ?? '';
      $prescription->getPatient($searchQuery);
    }
    ?>
    
</div>
<div class="modal" id="prescriptionModal">
      <div class="modal-content">
        <form method="POST" action="add_prescription.php">
            <p class="modal-title">Prescribe Drugs </p>
            <div class="modal-field">
            <label for="patientUsername">Patient Username:</label>
            <input type="text" id="patientUsername" name="patientUsername" readonly>
            </div>
          

          <input type="hidden" id="patientId" name="patientId">
        <div class="modal-field">
          <label for="prescription">Prescription:</label>
          <textarea id="prescription" name="prescription" rows="4" cols="50"></textarea>
      </div>
      <div class="modal-field">
          <label for="prescriptionDate">Prescription Date:</label>
          <input type="date" id="prescriptionDate" name="prescriptionDate" value="<?= date('Y-m-d') ?>" />
        </div>
          <div class="modal-buttons">
                    <button type="submit" value="Submit">Submit</button>
            </div>
        </form>
      </div>
    </div>


    <script>
      // JavaScript code to handle the "Prescribe" button click
      document.addEventListener("DOMContentLoaded", function() {
        const prescribeButtons = document.querySelectorAll(".prescribe-button");
        const modal = document.getElementById("prescriptionModal");
        const patientIdField = document.getElementById("patientId");
        const patientUsernameField = document.getElementById("patientUsername");

        prescribeButtons.forEach(function(button) {
          button.addEventListener("click", function() {
            const patientId = this.dataset.patientId;
            const patientUsername = this.dataset.username;
            patientIdField.value = patientId;
            patientUsernameField.value = patientUsername;
            modal.style.display = "block";
          });
        });

        // Close the modal when clicked outside the content
        window.addEventListener("click", function(event) {
          if (event.target === modal) {
            modal.style.display = "none";
          }
        });
      });
    </script>

  </body>
</html>