<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require 'helper/PHPMailer/src/Exception.php';
require 'helper/PHPMailer/src/PHPMailer.php';
require 'helper/PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = SMTP :: DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'aiydwijitr@gmail.com';                     // SMTP username
    $mail->Password   = '1801300264064';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail -> setFrom ( 'aiydwijitr@gmail.com' , '4_9_10_Theerapet Aiydwijitr' );
    $mail -> addAddress ( 'jzaza05@gmail.com' , 'jo' );     // เพิ่มผู้รับ
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'ใบแจ้งชำระค่าบริการเว็บไซต์รายปี';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>