<?php
require("db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $pattern = "1234567890";
    $length = strlen($pattern);
    $v_code = [];

    for ($i = 0; $i < 6; $i++) {
        $index = rand(0, $length - 1);
        $v_code[] = $pattern[$index];
    }

    $ver_code = implode($v_code);

    $full_name = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $check = "SELECT email FROM users WHERE email = '$email'";
    $response = $db->query($check);

    if ($response->num_rows != 0) {
        echo "usermatch";
    } else {
        $send_atc = mail($email, "Verification Code from NRA Pvt. Ltd."
, "Dear $full_name,\n\nYour verification code is: $ver_code\n\nWelcome to NRA Pvt. Ltd. services!", "FROM: narindekumer62@gmail.com");

        if ($send_atc) {
            $store = "INSERT INTO users (full_name, email, password, activation_code) VALUES ('$full_name', '$email', '$password', '$ver_code')";

            if ($db->query($store)) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            echo "Try again with Other Email ID";
            // Optional: Get the last error
            $error = error_get_last();
            echo "Error: " . $error['message'];
        }
    }
} else {
    echo "unauthorised request";
}
?>
