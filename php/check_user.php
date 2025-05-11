<?php

require("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email']; // Corrected key

    $check = "SELECT email FROM users WHERE email = '$email'"; // Corrected column name
    $response = $db->query($check);

    if ($response) {
        if ($response->num_rows != 0) {
            echo "user match";
        } else {
            echo "notfound";
        }
    } else {
        echo "Query failed: " . $db->error; // Added error handling
    }
} else {
    echo "unauthorised request";
}
?>
