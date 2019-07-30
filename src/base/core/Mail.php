<?php

namespace base\core;

use base\libraries\mailer\PHPMailer;
use base\libraries\mailer\Exception;

class Mail extends PHPMailer {

    public function __construct() {
        parent::__construct(true);
        $this->isSMTP();   
        $this->Host = \A::$app->config['mail']['host'];
        $this->SMTPAuth = true;  
        $this->Username = \A::$app->config['mail']['username'];
        $this->Password = \A::$app->config['mail']['password']; 
        $this->setFrom(\A::$app->config['mail']['from'][0], \A::$app->config['mail']['from'][1]);
        $this->SMTPSecure = \A::$app->config['mail']['SMTPSecure'];
        $this->Port = \A::$app->config['mail']['port'];
        $this->CharSet = 'UTF-8';
    }
    
    public function sendMessage($to, $subject, $body, $isHtml = false) {
        try {
            $this->isHTML($isHtml);
            $this->addAddress($to);
            $this->Subject = $subject;
            $this->Body = $body;
            $this->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->ErrorInfo;
        }
    }


    /*
      $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
      try {
      //Server settings
      $mail->SMTPDebug = 2;                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'user@example.com';                 // SMTP username
      $mail->Password = 'secret';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      //Recipients
      $mail->setFrom('from@example.com', 'Mailer');
      $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
      $mail->addAddress('ellen@example.com');               // Name is optional
      $mail->addReplyTo('info@example.com', 'Information');
      $mail->addCC('cc@example.com');
      $mail->addBCC('bcc@example.com');

      //Attachments
      $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Here is the subject';
      $mail->Body = 'This is the HTML message body <b>in bold!</b>';
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      echo 'Message has been sent';
      } catch (Exception $e) {
      echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }
      }
     * */

}
