<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/esp_conducteur.css">
    <style>
        /* Add your custom CSS styles for table presentation */
        table {
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include('../header.php'); ?>

    <div class="container">
        <?php include('nav_bar.php'); ?>
        <div class="main-content" id="mainContent">
            <!-- Your content related to managing cars and rides goes here -->
            <h2>Welcome to the Driver Space</h2>

            <?php
            include('../../connect.php');
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Check if num_escale is set and not empty
            if (isset($_GET['num_escale']) && !empty($_GET['num_escale'])) {
                // Sanitize the input to prevent SQL injection
                $num_escale = $_GET['num_escale'];

                // Perform a database query based on the NUM_TRAJET
                $query1 = "SELECT * FROM ESCALE WHERE NUM_ESCALE = ?";
                $stmt1 = $conn->prepare($query1);
                $stmt1->bind_param("i", $num_escale);
                
                if ($stmt1->execute()) {
                    $result1 = $stmt1->get_result();

                    if ($result1->num_rows > 0) {
                        echo '<div style="overflow-x:auto;">'; // Add a container for horizontal scrolling
                        echo '<table>';
                        $columns = ['NUM_ESCALE', 'NUM_TRAJET', 'ADRESSE', 'CODE_POSTAL', 'HEURE_ARRIVEE'];
                        while ($row = $result1->fetch_assoc()) {
                            foreach ($columns as $column) {
                                echo '<tr>'; // Start new table row
                                if ($column === 'NUM_TRAJET') {
                                    // Make NUM_TRAJET clickable with a link to view_trajet.php
                                    echo "<th>$column</th>";
                                    echo "<td><a href='view_trajet.php?num_trajet={$row[$column]}'>{$row[$column]}</a></td>";
                                } else {
                                    echo "<th>$column</th>";
                                    echo "<td>{$row[$column]}</td>"; // Display other columns vertically
                                }
                                echo '</tr>';
                            }
                        }
                        echo '</table>';
                        echo '</div>'; // Close the container for horizontal scrolling
                    } else {
                        echo "No result found";
                    }
                } else {
                    echo "Error executing query 1: " . $stmt1->error;
                }
                
                // Close statement
                $stmt1->close();
            }
            ?>

        </div>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
