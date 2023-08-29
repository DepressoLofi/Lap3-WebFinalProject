<?php
// Include the PHPMailer class and connect.php file
require 'PHPMailer.php';
require 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process your user registration form data here

    // Assuming you have obtained user data and generated an activation token
    $username = $_POST['username'];
    $email = $_POST['email'];
    $activationToken = generateActivationToken(); // Replace with your activation token logic

    // Insert user data into the database and send activation email
    $sql = "INSERT INTO users (username, email, activation_token) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $activationToken);

    if ($stmt->execute()) {
        // Send activation email
        $mail = new PHPMailer(true);

        // Set up PHPMailer with your SMTP server settings
        // ...

        $mail->addAddress($email, $username);
        $mail->Subject = 'Activate Your Account';
        $activationLink = 'https://yourwebsite.com/activate.php?token=' . $activationToken;
        $mail->Body = 'Click the following link to activate your account: ' . $activationLink;

        try {
            $mail->send();
            echo 'Activation email sent successfully!';
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Error inserting data into database: ' . $stmt->error;
    }
}
?>
