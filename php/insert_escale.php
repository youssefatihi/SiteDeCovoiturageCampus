<?php
include('../connect.php'); // Include the file for database connection
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form was submitted and if 'num_trajet' is set in the URL
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['num_trajet']) && isset($_POST['num_passager'])) {
    // Collect form data
    $adresse = $_POST['adresse'];
    $code_postal = $_POST['code_postal'];
    $num_trajet = $_GET['num_trajet'];
    $num_passager = $_POST['num_passager'];

    // Insert data into ESCALE table
    $query1 = "INSERT INTO ESCALE (NUM_TRAJET, ADRESSE, CODE_POSTAL) VALUES (?, ?, ?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("iss", $num_trajet, $adresse, $code_postal);

    // Execute the ESCALE query
    if ($stmt1->execute()) {
        $num_escale = $stmt1->insert_id; // Get the inserted ID of the newly added row

        // Insert data into PROPOSITION table
        $query = "INSERT INTO PROPOSITION (NUM_PASSAGER, NUM_ESCALE) VALUES (?, ?)";
        $stmt = $conn->prepare($query);

        // Bind values and execute the query for PROPOSITION
        $stmt->bind_param("ii", $num_passager, $num_escale); // Use $num_escale here as the NUM_ESCALE value
        if ($stmt->execute()) {
            header("Refresh:1; url=ajout_escale.php?num_trajet=$num_trajet");
            echo "New stopover added successfully! Redirection in progress...";
        } else {
            echo "Error inserting proposal: " . $stmt->error;
        }

        // Close PROPOSITION statement
        $stmt->close();
    } else {
        echo "Error when inserting the stopover:" . $stmt1->error;
    }

    // Close ESCALE statement
    $stmt1->close();
}

// Close the database connection
$conn->close();
?>

