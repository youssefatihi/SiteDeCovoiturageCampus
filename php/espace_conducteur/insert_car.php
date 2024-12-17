<?php
include('../../connect.php'); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['num_etudiant']) && !empty($_GET['num_etudiant'])) {
    $num_etudiant = $_GET['num_etudiant'];

    $num_immatricule = $_POST['num_immatricule'] ?? '';
    $type_voiture = $_POST['type_voiture'] ?? '';
    $couleur = $_POST['couleur'] ?? '';
    $etat = $_POST['etat'] ?? '';
    $nbr_passager = $_POST['nbr_passager'] ?? '';

    if (empty($num_immatricule) || empty($type_voiture) || empty($couleur) || empty($etat) || empty($nbr_passager)) {
        echo "Please complete all fields on the form.";
    } else {
        $query = "SELECT * FROM VOITURE WHERE NUM_IMMATRICULE = ?";
        $check = $conn->prepare($query);
        $check->bind_param("i", $num_immatricule);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "This car already exists!";
        } else {
            $query = "INSERT INTO VOITURE (NUM_IMMATRICULE, NUM_CONDUCTEUR, TYPE_VOITURE, COULEUR, ETAT, NBR_PASSAGER) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
        
            $stmt->bind_param("iisssi", $num_immatricule, $num_etudiant, $type_voiture, $couleur, $etat, $nbr_passager);
            if ($stmt->execute()) {
                echo "New car added successfully!";
            } else {
                echo "Erreur: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}

$conn->close();
?>

