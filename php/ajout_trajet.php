<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a trip</title>
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

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        h2 {
            text-align: center;
            color: black;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #2a5298; /* Dégradé de bleu pour hover */
        }

        button, input[type=submit] {
            background: #333; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
            transition: background-color 0.3s, transform 0.3s;
        }

        .addTrajetForm {
            text-align: center;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .form-group input {
            flex: 0 0 48%; /* Pour que deux champs soient sur la même ligne */
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s, transform 0.3s;
        }

        .submit-button:hover {
            background-color: #555;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .form-group input {
                flex: 0 0 100%;
            }
        }

    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <?php include('../connect.php'); ?>

    <div id="addTrajetForm" class="form-container">
        <h2>Add a new trip</h2>
        <form action="insert_trajet.php" method="post" class="form-trajet">
            <div class="form-group">
                <input type="text" name="num_etudiant" placeholder="Student number" required>
                <input type="text" name="num_immatricule" placeholder="Plate number" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_depart" id="leaveon" placeholder="Departure date" required>
                <input type="date" name="date_arrivee" id="arriveon" placeholder="Arrival date" required>
            </div>
            <div class="form-group">
                <input type="text" name="adresse_arrivee" placeholder="Arrival adress" required>
                <input type="text" name="code_postal" placeholder="Postal code" required>
            </div>
            <div class="form-group">
                <input type="text" name="prix_kilometrage" placeholder="KM price" required>
                <input type="text" name="distance_total" placeholder="Total distance" required>
            </div>
            <div class="form-group">
                <input type="text" name="duree_estime" placeholder="Estimated duration" required>
                <input type="text" name="nbr_escales" id="nbr_escales" placeholder="Number of stops" required>
            </div>

            <div id="escaleInputs"> <!-- Placeholder for escale inputs -->
                <!-- Escale inputs will be added here -->
            </div>

            <button type="button" onclick="addEscale()" class="submit-button">Add Escale</button>
            <button type="submit" class="submit-button">Add trip</button>
        </form>
    </div>
    <script>
        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum date for departureDate input
        document.getElementById('leaveon').setAttribute('min', today);

        // Function to set the minimum date for arrivalDate input based on departureDate selection
        document.getElementById('leaveon').addEventListener('change', function() {
            document.getElementById('arriveon').setAttribute('min', this.value);
        });
    </script>

    <script>
        function addEscale() {
            const nbr_escales = document.getElementById('nbr_escales').value;
            const escaleInputs = document.getElementById('escaleInputs');

            escaleInputs.innerHTML = ''; // Clear previous inputs

            for (let i = 0; i < nbr_escales; i++) {
                const div = document.createElement('div');
                div.className = 'form-group';
                div.innerHTML = `
                    <input type="text" name="adresse${i}" placeholder="Adresse Escale ${i + 1}" required>
                    <input type="text" name="code_postal${i}" placeholder="Code postal Escale ${i + 1}" required>
                `;
                escaleInputs.appendChild(div);
            }
        }
    </script>
</body>
</html>