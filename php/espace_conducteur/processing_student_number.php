
/* heeeeeeeeeelp */
<form id="studentNumberForm">
                    <div class="form-group">
                        <label for="num_etudiant">Student Number:</label>
                        <input type="text" id="num_etudiant" name="num_etudiant" placeholder="Enter your student number" required>
                    </div>
                    <div class="form-group">
                        <input type="button" value="Submit" onclick="submitForm()">
                    </div>
                </form>
            </div>
            <!-- Content dynamically loaded using AJAX -->
            <div id="dynamicContent">
                This is the default content. Click on the navigation items to view different content.
            </div>
        </div>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        function submitForm() {
            var num_etudiant = document.getElementById("num_etudiant").value; // Get the num_etudiant value
            
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("dynamicContent").innerHTML = this.responseText;
                }
            };

            
        }

        function showContent(type) {
            var num_etudiant = document.getElementById("num_etudiant").value;

            // Remove 'active' class from all navigation items
            var navItems = document.querySelectorAll('.sidebar li');
                navItems.forEach(function(item) {
                item.classList.remove('active');
            });

            // Add 'active' class to the clicked navigation item
            event.currentTarget.parentElement.classList.add('active');

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("dynamicContent").innerHTML = this.responseText;
            }
            };

            // Load content based on the clicked navigation item
            if (type === 'Mon véhicule') {
                xhttp.open("GET", "info_vehicule.php?num_etudiant=" + num_etudiant, true);
                xhttp.send(); // Sending the request after setting up the URL
            
            } else if (type === 'Mes trajets à venir') {
                xhttp.open("GET", "upcoming_trips.php", true);
                xhttp.send();
            } else if (type === 'Demandes de réservations') {
                xhttp.open("GET", "reservation_requests.php", true);
                xhttp.send();
            } else if (type === 'Propositions d\'escales') {
                xhttp.open("GET", "proposed_stops.php", true);
                xhttp.send();
            }
        }
    </script>
</body>
