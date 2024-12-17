<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 60px;
            background-color: #ffffff; /* Fond blanc */
            color: #000000; /* Texte noir */
            margin: 0;
        }
        .contact-container {
            background-color: #f8f8f8; /* Fond légèrement gris pour le contraste */
            width: 80%;
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Ombre plus marquée pour le relief */
            border-radius: 8px;
        }
        .contact-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .contact-form > div {
            flex: 0 0 48%;
            margin-bottom: 20px;
        }
        .contact-form input[type='text'],
        .contact-form input[type='email'],
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc; /* Bordure plus claire */
            border-radius: 4px;
            margin-bottom: 20px;
            background-color: #fff; /* Fond blanc pour les champs */
            color: #000; /* Texte noir pour les champs */
        }
        .contact-form textarea {
            resize: vertical;
            height: 150px;
        }
        .contact-form button[type='submit'] {
            padding: 10px 20px;
            background-color: #000000; /* Bouton noir */
            color: #ffffff; /* Texte blanc sur le bouton */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .contact-form button[type='submit']:hover {
            background-color: #333333; /* Gris foncé au survol */
        }
        .contact-heading {
            text-align: center;
            margin-bottom: 40px;
            color: #000000; /* Titre en noir */
        }
    </style>
</head>
<body>
<?php include('../php/header.php'); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        // Handle error here
        echo "Invalid input!";
    } else {
        $to = "mohamedbouhaja106@gmail.com"; // REPLACE with your email address
        $subject = "New contact from $name";
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";

        $email_headers = "From: $name <$email>";

        if (mail($to, $subject, $email_content, $email_headers)) {
            // Email sent successfully
            echo "Thank you! Your message has been sent.";
        } else {
            // Email failed to send
            echo "Oops! Something went wrong, we couldn't send your message.";
        }
    }
}
?>

<div class="contact-container">
    <h1 class="contact-heading">Contact us</h1>
    <form class="contact-form" action="contact.php" method="post">
        <div>
            <input type="text" id="name" name="name" placeholder="last name" required>
        </div>
        <div>
            <input type="email" id="email" name="email" placeholder="Your email" required>
        </div>
        <div style="flex: 0 0 100%;">
            <textarea id="message" name="message" placeholder="your message" required></textarea>
        </div>
        <div style="flex: 0 0 100%; text-align: center;">
            <button type="submit">Send</button>
        </div>
    </form>
</div>
    <?php include('../php/footer.php'); ?>

</body>
</html>
