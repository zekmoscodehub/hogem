<?php
// Start the session
session_start();
require 'connect.php';
require 'login_function.php';
// Check if the user is already logged in
?>

<!DOCTYPE html>
<html>
<head>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
    <meta property="HOUSE OF GRACE APP" content="HOUSE OF GRACE- House Of Grace Church" /> <!-- website name -->
    <meta property="https://wateredgardenchurch.org/" content="House Of Grace Church Ghana" /> <!-- website link -->
    <meta property="HOUSE OF GRACE" content=" HOUSE OF GRACE- House Of Grace Church" /><!-- title shown in the actual shared post -->
        <title>Login Page- HOUSE OF GRACE</title>
        <link rel="shortcut icon" href="assets/img/logo1.png">
        
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
    
</head>
<style>
  img.logo{
    border-radius: 50%;
    width: 120px;
    background: transparent ;
    margin-top: 40px;
}
.for{
  margin-bottom:-12px;
  /* padding:10em; */
}
</style>
<body>

  <?php include 'header.php'; ?>

  <section class="center-text form "style="margin:30px">
      <img src="assets/img/logo1.png" class="logo" alt="alt"style="margin-top:-30px"/><br><br>
      <strong style="margin-top: -20px">Log In</strong>

    <div class="login-form box-center form">
      <?php

        if(isset($_SESSION['prompt'])) {
          showPrompt();
        }

        if(isset($_SESSION['errprompt'])) {
          showError();
        }

      ?>

       <form class="for" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            
                          
                                
                                <button type="submit" class="btn btn-primary sign-btn"style="margin-left:auto;margin-top: 5px; margin-bottom: 10px;">Login</button><br><!-- comment -->
                                  <div>forgot password?
                            <a href="password_reset_request.php" class="btn"> Click here</a>
                            <div>Not registered ?<a href="join.php"> Register<a> </div>
                            </div>
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


