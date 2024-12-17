<?php
include('../connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_etudiant = $_POST['num_etudiant'];
    $num_immatricule = $_POST['num_immatricule'];
    $date_depart = $_POST['date_depart'];
    $date_arrivee = $_POST['date_arrivee'];
    $distance_total = $_POST['distance_total'];
    $adresse_arrivee = $_POST['adresse_arrivee'];
    $code_postal = $_POST['code_postal'];
    $prix_kilometrage = $_POST['prix_kilometrage'];
    $nbr_escales = $_POST['nbr_escales'];
    $duree_estime = $_POST['duree_estime'];

    // Start a transaction
    $conn->begin_transaction();

    // Prepared statements
    $stmt1 = $conn->prepare("INSERT IGNORE INTO CONDUCTEUR VALUES (?)");
    $stmt1->bind_param("i", $num_etudiant);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $conn->prepare("INSERT INTO VOITURE (NUM_IMMATRICULE, NUM_CONDUCTEUR) VALUES (?, ?)");
    $stmt2->bind_param("ii", $num_immatricule, $num_etudiant);
    $stmt2->execute();
    $stmt2->close();

    $stmt3 = $conn->prepare("INSERT INTO TRAJET (NUM_IMMATRICULE, DATE_DEPART, DATE_ARRIVEE, ADRESSE_ARRIVEE, CODE_POSTAL, NBR_ESCALES, PRIX_KILOMETRAGE, DISTANCE_TOTAL, DUREE_ESTIME) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt3->bind_param("isssiiiii", $num_immatricule, $date_depart, $date_arrivee, $adresse_arrivee, $code_postal, $nbr_escales, $prix_kilometrage, $distance_total, $duree_estime);

    $stmt4 = $conn->prepare("INSERT INTO ESCALE (NUM_TRAJET, ADRESSE, CODE_POSTAL, VALIDATION_ESCALE) VALUES (?, ?, ?, 1)");
    $stmt4->bind_param("iss", $num_trajet, $adresse, $code_postal);

    // Execute the third query
    if ($stmt3->execute()) {
        if ($nbr_escales > 0) {
            $num_trajet = $stmt3->insert_id;

            // Execute the fourth query for each escale
            for ($i = 0; $i < $nbr_escales; $i++) {
                if (!isset($_POST['adresse' . $i]) || !isset($_POST['code_postal' . $i])) {
                    $conn->rollback();
                    header("Refresh:1; url=ajout_trajet.php");
                    echo "Forms for stopovers must be completed.";
                    exit();
                }

                $adresse = $_POST['adresse' . $i];
                $code_postal = $_POST['code_postal' . $i];

                $stmt4->execute();
                if ($stmt4->affected_rows === 0) {
                    $conn->rollback();
                    echo "Error while inserting the stopover";
                    exit();
                }
            }
        }

        $conn->commit();
        header("Refresh:1; url=ajout_trajet.php");
        echo "New route added successfully! Redirection in progress...";
    } else {
        echo "Error: " . $stmt3->error;
    }

    // Close prepared statements
    $stmt3->close();
    $stmt4->close();
    $conn->autocommit(true);
}

$conn->close();
?>
