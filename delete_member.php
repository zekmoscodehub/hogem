<?php
// delete_member.php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the member ID and class from the URL parameters
    $member_id = $_GET['id'];
    $class = $_GET['class'];

    // Perform the deletion operation based on the provided ID and class
    // Implement your deletion logic here...

    // Redirect back to the page where the deletion was initiated
    header("Location: members.php");
    exit();
}
?>