<?php
include('../connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming the data sent via POST contains proper sanitation/validation
    $num_etudiant = $_POST['num_etudiant'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    // Prepare and execute the query to insert data into the ETUDIANT table
    $query = "INSERT IGNORE INTO ETUDIANT (NUM_ETUDIANT, NOM, PRENOM) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("iss", $num_etudiant, $nom, $prenom);
        $stmt->execute();

        // Check for successful insertion or any error
        if ($stmt->affected_rows > 0) {
            // Data inserted successfully
            echo "You are now part of our database!";
        } else {
            // An error occurred or data already exists (due to IGNORE)
            if ($stmt->errno == 1062) {
                echo "Error: You are already part of our database.";
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in preparing the statement
        echo "Error: Unable to prepare the statement.";
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>

