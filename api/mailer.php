<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function sendMail($to, $subject, $message)
    {
        require "https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php";
        require "https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php";
        require "https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php";
        require "mailerConfig.php";
    
        $mail = new PHPMailer(true);
        try
        {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            
            $mail->setFrom($username, "M-RPG");
            $mail->addAddress($to);
            
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
    
            $mail->send();
        }
        catch (Exception $e)
        {
            exitApi(500, "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
        $mail->smtpClose();
    }
?>