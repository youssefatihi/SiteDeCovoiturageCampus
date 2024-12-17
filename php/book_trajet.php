<?php
include('../connect.php'); // Inclure le fichier de connexion à la base de données
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['num_trajet'])) {
    // Collecter les données du formulaire
    $num_etudiant = $_POST['num_etudiant'];
    $num_trajet = $_GET['num_trajet'];

    // Préparer la requête SQL pour insérer le escale
    $query = "INSERT INTO RESERVATION (NUM_PASSAGER, NUM_TRAJET) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    
    // Associer les valeurs et exécuter la requête
    $stmt->bind_param("ii", $num_etudiant ,$num_trajet);
    if ($stmt->execute()) {
        header("Refresh:1; url=info_trajet.php?num_trajet=$num_trajet");
        echo "Reservation made successfully! Redirection in progress...";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Fermer la déclaration
    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>
