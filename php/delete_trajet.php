<?php
include('../connect.php'); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_trajet = $_POST['num_trajet'];

    // Préparer la requête SQL pour supprimer le trajet
    $query = "DELETE FROM TRAJET WHERE NUM_TRAJET = ?";
    $stmt = $conn->prepare($query);

    // Si la préparation de la requête a échoué, afficher une erreur
    if ($stmt === false) {
        die("Query preparation error:" . $conn->error);
    }

    // Associer le numéro du trajet et exécuter la requête
    $stmt->bind_param("i", $num_trajet);
    
    if ($stmt->execute()) {
        header("Refresh:1; url=test.php");
        echo "Trip deleted successfully! Redirection in progress...";
    } else {
        echo "Error while deleting:" . $stmt->error;
    }

    // Fermer la déclaration
}

// Fermer la connexion
$conn->close();
?>
