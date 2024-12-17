<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/table_style.css">
    <link rel="stylesheet" type="text/css" href="../../css/esp_conducteur.css">
</head>
<body>
    <?php include('../header.php'); ?>

    <div class="container">
        <?php include('nav_bar.php'); ?>
        <div class="main-content" id="mainContent">
            <h2>Evaluate Passenger</h2>

            <?php
            include('../../connect.php');
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Check if necessary POST parameters are set and not empty
            if (
                isset($_POST['num_passager'], $_POST['num_trajet'], $_POST['num_conducteur'], $_POST['note']) &&
                !empty($_POST['num_passager']) && !empty($_POST['num_trajet']) && !empty($_POST['num_conducteur']) && !empty($_POST['note'])
            ) {
                // Sanitize input to prevent SQL injection
                $num_passager = $_POST['num_passager'];
                $num_trajet = $_POST['num_trajet'];
                $num_conducteur = $_POST['num_conducteur'];
                $note = $_POST['note'];

                try {
                    // Check if an evaluation record exists for the provided passenger, trip, and driver
                    $query = "SELECT * FROM EVALUATION WHERE NUM_ETUDIANT_EVALUE = ? AND NUM_TRAJET = ? AND NUM_ETUDIANT_EVALUATEUR = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("iii", $num_passager, $num_trajet, $num_conducteur);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Update the existing evaluation record
                        $updateQuery = "UPDATE EVALUATION SET NOTE = ? WHERE NUM_ETUDIANT_EVALUE = ? AND NUM_TRAJET = ? AND NUM_ETUDIANT_EVALUATEUR = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("diii", $note, $num_passager, $num_trajet, $num_conducteur);
                        $updateStmt->execute();

                        
                        echo "<p>Evaluation updated successfully!</p>";
                    } else {
                        // Insert a new evaluation record
                        $insertQuery = "INSERT INTO EVALUATION (NUM_ETUDIANT_EVALUE, NUM_TRAJET, NUM_ETUDIANT_EVALUATEUR, NOTE) VALUES (?, ?, ?, ?)";
                        $insertStmt = $conn->prepare($insertQuery);
                        $insertStmt->bind_param("iiid", $num_passager, $num_trajet, $num_conducteur, $note);
                        $insertStmt->execute();
                        
                        
                        echo "<p>Evaluation submitted successfully!</p>";
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "<p>Parameters required for evaluation are missing or invalid.</p>";
            }
            ?>

        </div>
    </div>
    <?php include('../footer.php'); ?>
</body>
</html>

