<?php
namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 *
 */
class Mail {
    /**
     * Send the email
     * @param  String $recipient Email address of the recipient
     * @param  String $subject   Subject of the email
     * @param  String $msg       Body of the email
     * @return boolean           Mixed
     */
    public static function send($recipient, $subject, $msg) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $config = require(CONFIG_PATH . 'mail.php');
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $config['host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $config['username'];                 // SMTP username
            $mail->Password = $config['password'];                           // SMTP password
            $mail->SMTPSecure = $config['encryption'];                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $config['port'];                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($recipient);     // Add a recipient
            $mail->addReplyTo($config['from_email'], $config['from_name']);
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $msg;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            ob_start();
            $mail->send();
            ob_end_clean();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
