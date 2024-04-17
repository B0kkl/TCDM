<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $to_email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['message'];
    $headers = "From: kunjechrissy@gmail.com";

    // Send email
    if(mail($to_email, $subject, $body, $headers)){
        header('location: TCDMOffence.php');
    } else {
        echo "Email not sent.";
    }
}
?>