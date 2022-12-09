<?php

require_once('init.php');

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

if (isset($_POST) && !empty($_POST)) {
    // Checking For Blank Fields..
    if (isset($_POST["name"]) === false || isset($_POST["email"]) === false || isset($_POST["sub"]) === false || isset($_POST["msg"]) === false) {
        echo "Fill All Fields..";
    } else {
        // Check if the "Sender's Email" input field is filled out
        $email = $_POST['email'];
        // Sanitize E-mail Address
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Validate E-mail Address
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo "Invalid Sender's Email";
        } else {

            $transport = Transport::fromDsn('gmail+smtp://alexis.mathiot@gmail.com:mvmwqshtnuybceeh@default');
            $mailer = new Mailer($transport);
            $subject = $_POST['sub'];
            $message = $_POST['msg'];
            $message = wordwrap($message, 70);


            $email = (new Email())
                ->from($email)
                ->to('alexis.mathiot@gmail.com')
                ->subject($subject)
                ->html('<p>' . $message . '</p>');

            $mailer->send($email);
        }
    }
}



render('contact.twig', ['categories' => '']);
