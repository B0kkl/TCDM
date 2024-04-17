<?php

$to_email = "hmwachikumbah@gmail.com"; // Corrected email address
$subject = "send email via php";
$body = "hi, this is a test";
$headers = "From: kunjechrissy@gmail.com";

if(mail($to_email, $subject, $body, $headers)){
    echo "Email successfully sent to ".$to_email."....";
}
else{
    echo "not sent";
}
?>
