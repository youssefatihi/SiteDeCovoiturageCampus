<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
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

    <div id="signUPForm" class="form-container">
        <h2>Be part of our community</h2>
        <form action="insert_student.php" method="post" class="form-trajet">
            <div class="form-group">
                <input type="text" name="num_etudiant" placeholder="Numéro Etudiant" required>
            </div>
            <div class="form-group">
                <input type="text" name="prenom" placeholder="First name" required>
                <input type="text" name="nom" placeholder="Last name" required>
            </div>
            <button type="submit" class="submit-button">Sign Up</button>
        </form>
    </div>
</body>
</html>