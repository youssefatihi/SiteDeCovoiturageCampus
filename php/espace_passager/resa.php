<!DOCTYPE html>
<html>
<head>
    <title>Driver Space - Manage Car and Rides</title>
    <!-- Add your CSS or external stylesheet links here -->
    <link rel="stylesheet" type="text/css" href="../../css/esp_conducteur.css">
    <script>
        function fetchResultsFromDB() {
            var num_etudiant = document.getElementById('num_etudiant').value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("results").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "fetch_info_resa.php?num_etudiant=" + num_etudiant, true);
            xhttp.send();
        }
    </script>
</head>
<body>
    <?php include('../header.php'); ?>

    <div class="container">
        <?php include('nav_bar.php'); ?>
        <div class="main-content" id="mainContent">
            <!-- Your content related to managing cars and rides goes here -->
            <h2>Welcome to the Driver Space</h2>

            <form id="studentNumberForm" onsubmit="event.preventDefault(); fetchResultsFromDB();">
                <div class="form-group">
                    <label for="num_etudiant">Student Number:</label>
                    <input type="text" id="num_etudiant" name="num_etudiant" placeholder="Enter your student number" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit">
                </div>
            </form>

            <div id="results"></div>
        </div>
    </div>

    <?php include('../footer.php'); ?>
</body>
</html>
