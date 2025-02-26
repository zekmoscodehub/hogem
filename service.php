

        <?php
        session_start(); // Starting the session

        // Database connection
        require('connect.php');
        // Check if the user is logged in and has admin privileges
        if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
            // Redirect to another page or display an error message
            header("Location:index.php");
            exit(); // Ensure script execution stops
        }
        // Create the service_table if it doesn't exist
        $sql_create_table = "CREATE TABLE IF NOT EXISTS service_table (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                branch_name VARCHAR(255),
                                date DATE,
                                preacher VARCHAR(255),
                                message TEXT,
                                attendance INT,
                                ft INT,
                                fb INT,
                                nc INT,
                                children INT
                            )";

        if (mysqli_query($conn, $sql_create_table)) {
            // echo "Table created successfully or already exists.";
        } else {
            echo "Error creating table: " . mysqli_error($conn);
        }

        // Function to check if the branch name exists in the staff table
        function verifyBranchName($branch_name) {
            global $conn;
            $sql = "SELECT * FROM staff WHERE branch_name = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $branch_name);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                echo "Error in SQL statement";
            }
        }

        // Function to insert service records
        function insertServiceRecord($branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children) {
            global $conn;
            $sql = "INSERT INTO service_table (branch_name, date, preacher, message, attendance, ft, fb, nc, children) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssssiiiii", $branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['success'] = true; // Set session variable for success
                    header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
                    exit();
                } else {
                    echo "Error inserting record: " . mysqli_error($conn);
                }
            } else {
                echo "Error in SQL statement";
            }
        }

        // Check if success session is set and display alert
        if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
            echo "<script>alert('Record inserted successfully.');</script>";
            unset($_SESSION['success']); // Unset session variable
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $branch_name = isset($_POST['branch_name']) ? $_POST['branch_name'] : "";
            $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
            $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : "";

            // Proceed with processing the form data...
        }
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $branch_name = $_POST['branch_name'];
            $date = $_POST['date'];
            $preacher = $_POST['preacher'];
            $message = $_POST['message'];
            $attendance = $_POST['attendance'];
            $ft = $_POST['ft'];
            $fb = $_POST['fb'];
            $nc = $_POST['nc'];
            $children = $_POST['children'];

            // Check if the branch name exists
            if (verifyBranchName($branch_name)) {
                // If branch name exists, insert the service record
                insertServiceRecord($branch_name, $date, $preacher, $message, $attendance, $ft, $fb, $nc, $children);
            } else {
                // If branch name doesn't exist, alert the user
                echo "<script>alert('Please use the appropriate branch name.');</script>";
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Church Service Reports</title>
           
            <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
            <!-- <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstra.css"> -->
            <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-+fqpP2ZDxLg7gyjtCprz+TazlK9pCNhYLlORokjR5tXUtn5ax/+F/Jhb3/Th4XfjReJsqXwDCAlcZ3cDk2Qlg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
            <style>
                * {
                    box-sizing: border-box;
                }

                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f7f7f7;
                    color: #333;
                }

                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                    width:40%;
                }

                h2 {
                    text-align: center;
                    margin-top: 20px;
                    margin-bottom: 30px;
                }

                form {
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                label {
                    font-weight: bold;
                }

                input[type="text"],
                input[type="date"],
                input[type="number"],
                textarea {
                    width: 100%;
                    padding: 10px;
                    margin-top: 5px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    resize: vertical;
                }

                input[type="submit"] {
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }

                input[type="submit"]:hover {
                    background-color: #0056b3;
                }

                .menu {
                    /* text-align: center; */
                    margin-top: 20px;
                    margin-bottom:20px;
                    width:100Vmin;
                }

                .menu a {
                    margin: 0 10px;
                    padding: 10px 12px;
                    background-color: #007bff;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }

                .menu a:hover {
                    background-color: #0056b3;
                }

                @media screen and (max-width: 768px) {
                    form,label{
                        padding: 10px;
                        width:100Vmin;
                        
                    }
                    .menu{
                        margin-bottom:20px;
                    }
                }
                
            </style>
        </head>
        <body>
                   <div class="container">
            
                <h2>House of Grace Service Report</h2>
                <div class="menu">
                <a href="admin-services-report.php">Upload report</a>                    
                    <a href="service-attendance-report.php"><i class="fas fa-book"></i>View Service And Attendance</a>
                    <a href="admin_main.php"><i class="fas fa-home"></i>Home</a>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="branch_name">Branch Name:</label>
                    <input type="text" id="branch_name" name="branch_name"required>

                    <label for="date">Service Date:</label>
                    <input type="date" id="date" name="date"required>

                    <label for="preacher">Preacher:</label>
                    <input type="text" id="preacher" name="preacher"required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" placeholder="Message Title and Bible Verse's"required></textarea>

                    <label for="attendance">Attendance:</label>
                    <input type="number" id="attendance" name="attendance"placeholder="Adults">

                    <label for="ft">First Timers:<i>(if any)</i></label>
                    <input type="number" id="ft" name="ft">

                    <label for="fb">Follow Ups:<i>(if any)</i></label>
                    <input type="number" id="fb" name="fb">

                    <label for="nc">New Converts <i>(if any)</i>:</label>
                    <input type="number" id="nc" name="nc">

                    <label for="children">Children:</label>
                    <input type="number" id="children" name="children">

                    <input type="submit" value="Submit">
                </form>
            </div>
        </body>
        </html>
