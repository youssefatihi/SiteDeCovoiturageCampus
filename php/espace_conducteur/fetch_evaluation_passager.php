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
                isset($_POST['num_passager'], $_POST['num_trajet'], $_POST['num_conducteur'])
            ) {
                // Sanitize input to prevent SQL injection
                $num_passager = $_POST['num_passager'];
                $num_trajet = $_POST['num_trajet'];
                $num_conducteur = $_POST['num_conducteur'];

                if (isset($_POST['note'])) {
                    $note = $_POST['note'];

                    try {
                        // Update the note value or insert a new record
                        $update_query = "INSERT INTO EVALUATION (NUM_ETUDIANT_EVALUE, NUM_TRAJET, NUM_ETUDIANT_EVALUATEUR, NOTE) 
                                        VALUES (?, ?, ?, ?)
                                        ON DUPLICATE KEY UPDATE NOTE = ?";
                        $update_stmt = $conn->prepare($update_query);
                        $update_stmt->bind_param("iiiss", $num_passager, $num_trajet, $num_conducteur, $note, $note);

                        if ($update_stmt->execute()) {
                            echo "<p>Note updated successfully!</p>";
                        } else {
                            echo "<p>Failed to update the note.</p>";
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
            } else {
                echo "<p>Parameters required for evaluation are missing or invalid.</p>";
            }
            ?>

            <!-- Display the form to submit the note -->
            <form method="post" action="evaluer_passager.php">
                <input type="hidden" name="num_passager" value="<?php echo $_POST['num_passager']; ?>">
                <input type="hidden" name="num_trajet" value="<?php echo $_POST['num_trajet']; ?>">
                <input type="hidden" name="num_conducteur" value="<?php echo $_POST['num_conducteur']; ?>">
                <label for="note">Note:</label>
                <input type="text" id="note" name="note">
                <input type="submit" value="Submit">
            </form>

        </div>
    </div>
    <?php include('../footer.php'); ?>
</body>
</html>
