<?php
// Start the session

require('session.php');
require('connect.php');
$allowed_user_types = array('Branch_Pastor','Assistant_Pastor','Admin');
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], $allowed_user_types)) {
    header('location:login.php');
    exit; // Terminate script execution after redirection
}
?>

<!DOCTYPE html>
<html>
    <head>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="HOUSEOFGRACE" content="HOUSEOFGRACE" /> <!-- website name -->
        <meta property="HOUSEOFGRACE" content="Church in Ghana" /> <!-- website link -->
        <meta property="Church Management System" content="HouseofGrace Church App" /> <!-- title shown in the actual shared post -->
        <link rel="shortcut icon" href="assets/img/logo.jpg"><!-- title shown in the actual shared post -->
        <title>View Members</title>

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/main.css" rel="stylesheet">
        <!-- Your CSS and JavaScript files go here -->
        <link href="app.css" rel="stylesheet">
        <script src="app.js"></script>
    </head>
    <body>

        <?php include 'header.php'; ?>

        <section class="center-text form">
            <img src="assets/img/logo1.png" class="logo-main" alt="alt"style="margin-top:-30px"/><br>
            <strong style="margin-top: -20px">Branch Members</strong>

            <div class="login-form box-center form">
                <?php
                if (isset($_SESSION['prompt'])) {
                    showPrompt();
                }

                if (isset($_SESSION['errprompt'])) {
                    showError();
                }
                ?>

                <form method="post" action="member-view.php">
                    <select name="branch_region" id="branch_region" class="form-control" style="width: 50%; margin: 10px auto;">
                        <option value="all_members">View All members</option>
                        <option value="Bolga East">Bolga East</option>
                        <option value="Bolga West">Bolga West</option>
                       <option value="Bolga Central">Bolga Municipal</option>
                        <!-- <option value="Westhern">Westhern</option>
                        <option value="Kumasi">Ashanti  </option>
                        <option value="Bolgatanga">Upper East</option>
                        <option value="Easten">Ho</option>
                        <option value="Upper East">Easten</option>
                        <option value="Savana">Savanna</option> -->
                        <!-- Add other class options here -->
                    </select>
                    <button type="submit" class="btn btn-primary" style="width: 50%; margin: 10px 25%">Search</button>
                </form>
            </div>

        </section>

        <script src="assets/js/jquery-3.1.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>

<!-- Include your service worker registration script -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
                .then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function (error) {
                    console.error('Service Worker registration failed:', error);
                });
    }
</script>
</body>
</html>


