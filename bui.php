<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Admin Confirm Page</title>
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <style>
        body, h1, h2, h3, p, ul, li {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            min-width: 160vmin;
            justify-content: center;
            /* text-align: center; */
        }
        a{
            text-decoration:none;
        }
        .btn {
            font-size: 14px;
            padding: 5px 10px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        table {
           min-width: 160vmin;
            border-collapse: collapse;
            margin: 20px;
            
        }
        th, td {
            padding: 7px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        @media screen and (max-width: 600px) {
            .btn {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<div class="container box">
    <?php
    require_once 'connect.php';
    $conn = mysqli_connect($hostname, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['confirm_username'])) {
            $username = $_POST['confirm_username'];
            $update_query = "UPDATE staff SET confirmed = 'Yes' WHERE username = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("s", $username);
            $stmt_update->execute();
            $stmt_update->close();
        } elseif (isset($_POST['delete_username'])) {
            $username = $_POST['delete_username'];
            $delete_query = "DELETE FROM staff WHERE username = ?";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param("s", $username);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
    }

    $query = "SELECT branch_region, user_type, username, email, password, branch_name, mobile, confirmed FROM staff WHERE confirmed = 'No'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="admin-users">';
        echo '<h2>Admin Approvals Page</h2>';
        echo '<table>';
        echo '<tr><th>Region</th><th>Role</th><th>Username</th><th>Email</th><th>Password</th><th>Branch Name</th><th>Mobile</th><th>Action</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['branch_region'] . '</td>';
            echo '<td>' . $row['user_type'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['password'] . '</td>';
            echo '<td>' . $row['branch_name'] . '</td>';
            echo '<td>' . $row['mobile'] . '</td>';
            echo '<td>';
            echo '<button onclick="confirmRegistration(this, \'' . $row['username'] . '\')"class="btn btn-success;" style="background-color: green; margin:7px;" >Confirm</button> ';
            echo '<button onclick="deleteUser(this, \'' . $row['username'] . '\')" class="btn btn-danger;" style="background-color: red;">Delete</button>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>No unapproved users.</p>';
    }

    mysqli_close($conn);
    ?>
    <script>
        function confirmRegistration(button, username) {
            button.disabled = true;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    button.innerText = 'Confirmed';
                    button.disabled = true;
                }
            };
            xhr.send('confirm_username=' + username);
        }

        function deleteUser(button, username) {
            if (confirm("You are about to delete " + username + ". Confirm Yes to delete or Cancel.")) {
                button.disabled = true;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        button.parentElement.parentElement.remove();
                    }
                };
                xhr.send('delete_username=' + username);
            }
        }
    </script>
    <a class="btn btn-info" href="admin_main.php">Back</a>
</div>
</body>
</html>
