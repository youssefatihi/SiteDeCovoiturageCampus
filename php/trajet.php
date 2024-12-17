<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trips</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-top: 60px;
        background-color: #f2f2f2; /* Fond légèrement gris */
        margin: 0;
        color: #000000; /* Texte noir pour le contraste */
    }

    header {
        width: 100%;
        position: fixed;
        z-index: 1000;
        background: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
    }

    .row {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .column {
        flex: 1;
        padding: 10px;
        text-align: center;
    }

    .button {
        background-color: #333; /* Boutons en gris foncé */
        color: white;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s, transform 0.3s;
    }

    .button:hover {
        background-color: #555; /* Gris plus clair au survol */
        transform: scale(1.05);
    }

    #container {
        width: 80%;
        margin: 100px auto;
        background: white;
        padding: 100px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-size: 0.8rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #333;
        color: white;
    }

    tr:hover {
        background-color: #ddd; /* Fond gris clair au survol */
    }

    button, input[type=submit] {
        background-color: #333;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin: 10px 0;
    }

    .form-section input {
        width: calc(50% - 20px);
        padding: 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    @media screen and (max-width: 600px) {
        .form-section input {
            width: calc(100% - 20px);
        }
    }
</style>

</head>
<body>
    <?php include('header.php'); ?>
    <?php include('../connect.php'); ?>

    <div id="container">
        <form method="post">
            Destination: <input type="text" name="ville" required>
            Departure date: <input type="date" name="date1" id="leave" required>
            Arrival date: <input type="date" name="date2" id="arrive" required>
            <button type="submit">Show trips</button>
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
            $ville = $_POST['ville'];

            $dateSQL1 = date('Y-m-d', strtotime($date1));
            $dateSQL2 = date('Y-m-d', strtotime($date2));

            $stmt = $conn->prepare("SELECT * FROM TRAJET WHERE DATE_DEPART BETWEEN ? AND ? AND ADRESSE_ARRIVEE = ?;");
            $stmt->bind_param("sss", $dateSQL1, $dateSQL2, $ville);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>Trip number</th><th>Plate number</th><th class="date">DEPARTURE_DATE</th><th>ARRIVAL_DATE</th>
            <th>ARRIVAL_ADDRESS</th><th>POSTAL CODE</th><th>NBR_ESCALE</th><th>KM PRICE</th><th>DISTANCE_TOTAL</th><th>ESTIMATED_DURATION</th></tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="clickable-row" data-href="info_trajet.php?num_trajet=' . $row["NUM_TRAJET"] . '">';
                    echo '<td>' . $row["NUM_TRAJET"] . '</td>';
                    echo '<td>' . $row["NUM_IMMATRICULE"] . '</td>';
                    echo '<td>' . $row["DATE_DEPART"] . '</td>';
                    echo '<td>' . $row["DATE_ARRIVEE"] . '</td>';
                    echo '<td>' . $row["ADRESSE_ARRIVEE"] . '</td>';
                    echo '<td>' . $row["CODE_POSTAL"] . '</td>';
                    echo '<td>' . $row["NBR_ESCALES"] . '</td>';
                    echo '<td>' . $row["PRIX_KILOMETRAGE"] . '</td>';
                    echo '<td>' . $row["DISTANCE_TOTAL"] . '</td>';
                    echo '<td>' . $row["DUREE_ESTIME"] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<div id="result"><h3>No trips found for this city on the given date.</h3></div>';
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>


    <script>
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date for departureDate input
        document.getElementById('leave').setAttribute('min', today);

        // Function to set the minimum date for arrivalDate input based on departureDate selection
        document.getElementById('leave').addEventListener('change', function() {
            document.getElementById('arrive').setAttribute('min', this.value);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var rows = document.querySelectorAll(".clickable-row");

            rows.forEach(function(row) {
                row.addEventListener("click", function() {
                    var href = this.dataset.href;
                    if (href) {
                        window.location.href = href;
                    }
                });
            });
        });
</script>

</body>
</html>
