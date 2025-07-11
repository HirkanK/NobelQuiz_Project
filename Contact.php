<?php
include('session_top.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    
    $to = "kush.k1475963@gmail.com";  
    $subject = "New Contact Form Message";
    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "Content-type: text/plain; charset=UTF-8";

    $body = "You received a message from your website:\n\n" .
            "Name: $name\n" .
            "Email: $email\n\n" .
            "Message:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='../Contact_Us.html';</script>";
    } else {
        echo "<script>alert('Message could not be sent. Please try again.'); window.location.href='../Contact_Us.html';</script>";
    }
} else {
    echo "Invalid request.";
}
?>
