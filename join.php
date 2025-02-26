<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Description of your website">
    <meta name="keywords" content="keywords, for, SEO">
    <!-- <link rel="stylesheet" href="./assets/css/admin.css"> -->
    <title>Join HOUSE OF GRACE</title>
   
   <style>
    /* Resetting default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Setting up base styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.content {
    text-align: center;
}

.main-img img {
    max-width: 100%;
    height: auto;
}

.title {
    font-size: 24px;
    margin-bottom: 20px;
}

.choice-box {
    display: flex;
    /* flex-wrap: wrap; */
    justify-content: center;
    flex-direction: column;
    margin: 10px auto;
        width: 400px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px;
    text-decoration: none;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-secondary {
    background-color: #6c757d;
}

.btn-success {
    background-color: #28a745;
}

.btn1 {
    background-color: #007bff;
}

.btn:hover {
    background-color: #5a6268;
}

.btn-are {
    font-size: 16px;
    margin-top: 10px;
    text-decoration: none;
}

footer.top {
    margin-top: 10px;
    font-size: 14px;
    text-align: center;
}

.aa,
.bb {
    color: #6c757d;
}
img.logo{
    border-radius: 50%;
    width: 120px;
    background: transparent ;
}

   </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="main-img">
                <img src="assets/img/logo.png" alt="" class="logo"style="margin-top:30px">
            </div>
            <br>
            <div class="damin">
                <h2 class="title">Please Select Your Membership type</h2>
                <div class="choice-box">
                    <a href="member-register.php" class="btn btn-secondary">Church Member / Church Worker</a>
                    <a href="register.php" class="btn btn-success admin">Branch Pastor / Admin</a><br>
                    <div class="btn-area">Already have an account?<br><a class="btn btn-success btn-are" href="login.php">Login <i class="fas fa-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
    </div><br>
    <br>
    <br>
    <br>
    <br>
    <footer class="top">
        <span class="aa"> This App is Developed by: </span>Mr. Moses Zekrumah <br> 
        <span class="bb"> &copy; Zekmos Code Hub CLI - Ghana</span>
    </footer>
</body>
</html>

