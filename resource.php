<?php
// Start the session
session_start();
require 'connect.php';
// require 'login_function.php';
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

  
  <section class=" center-text form "style="margin:10px">
      <img src="assets/img/logo1.png" class="logo" alt="alt"style="margin-top:10px"/><br><br>
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
                            
                            <div class="mb-5">
                            <div class="upload-form">
        <h1 class="text-center">Upload Resource</h1>
        <hr>
            <form method="post" action="uploads.php" enctype="multipart/form-data">

            <div class="form-group">
                <label for="branch_name">Resource Name</label>
                <input type="text" id="branch_name" class="form-control" name="branch_name" required>
            </div>

            <!-- <div class="form-group">
                <label for="note_title">Service Date</label>
                <input type="date" id="note_title" class="form-control" name="note_title" required>
            </div> -->

            <div class="form-group">
                <!-- <label for="file_upload">Upload Report</label> -->
                <input type="file" id="file_upload" class="file-upload" name="file_upload" accept=".pdf, .jpeg, .jpg, .png, .gif, .docx" required>
                <label for="file_upload" class="file-upload-label" style="margin-top:20px;"><sub><i>pdf, jpeg, png, gif, docx etc.</i></sub></label><br>
                <button type="submit" class="btn btn-primary btn-block"style="margin-top:20px;">Upload File</button>
            </div>

           
        </form>
    </div>
    </div>

  </section>

  <script src="assets/js/jquery-3.1.1.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

    <!-- Include your service worker registration script -->
   
</body>
</html>


