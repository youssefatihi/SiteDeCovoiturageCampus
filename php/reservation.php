<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-top: 60px;
        background-color: #ffffff; /* Fond blanc */
        color: #000000; /* Texte noir pour le contraste */
        margin: 0;
    }

    #container {
        width: 80%;
        margin: 20px auto;
        background-color: #fff; /* Fond blanc pour le container */
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        color: black; /* Texte en noir pour la lisibilité */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #333; /* En-têtes de table en gris foncé */
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2; /* Alternance de couleur pour les lignes */
    }

    tr:hover {
        background-color: #ddd; /* Couleur lors du survol d'une ligne */
    }

    button, .submit-button {
        background-color: #333; /* Boutons en gris foncé */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    button:hover, .submit-button:hover {
        background-color: #555; /* Gris plus clair au survol */
    }

    h2 {
        text-align: center;
        color: #333; /* Titre en gris foncé */
    }

    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .form-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .form-group input {
        flex: 1 0 48%; /* Adaptation pour la réactivité */
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    @media screen and (max-width: 600px), (max-width: 768px) {
        .form-group input {
            flex: 0 0 100%; /* Pleine largeur sur petits écrans */
        }
    }
</style>

</head>
<body>
    <?php include('header.php'); ?>
    <?php include('../connect.php'); ?>

    <div id="addPassagerForm" class="form-container">
        <h2>Add a new passenger</h2>
        <form action="insert_passager.php" method="post" class="form-passager">
            <div class="form-group">
                <input type="text" name="num_etudiant" placeholder="Student number" required>
                <input type="text" name="PRENOM" placeholder="First name" required>
                <input type="text" name="NOM" placeholder="Last name" required>
            </div>
            <button type="submit" class="submit-button">Add passenger</button>
        </form>
    </div>

    <script>
        document.getElementById('showTableBtn').addEventListener('click', function() {
            var tableContainer = document.getElementById('tableContainer');
            tableContainer.style.display = tableContainer.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
