

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <style>
        /* footer.css */


html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

body {
    min-height: 100vh; /* Utilisez vh (Viewport Height) pour couvrir au moins la hauteur totale de la fenêtre de visualisation */
}

#footer {
    width: 100%;
    height: 50px;
    color: white;
    padding: 10px 0;
    text-align: center;
    background-color: #222222;
    border-top: solid #444444 8px;
    box-shadow: 0 -5px 5px #858585;
    color: #CCCCCC;
    font-size: 12px; 
    bottom: 0;
    position: fixed;

}




    </style>
</head>
<body>
    <div id="footer">
        CC-EIRB carpooling at your service, from <?php echo date("Y"); ?><br />
        © NERDS I2
    </div>
</body>
</html>
