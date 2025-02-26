<?php
$host = 'localhost';
$username = 'root';  // your DB username
$password = '';  // your DB password
$dbname = 'hge_news_events';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
