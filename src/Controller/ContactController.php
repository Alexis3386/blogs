<?php

namespace App\Controller;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class ContactController extends BaseController
{

    public function showContact(): void
    {
        $this->view('contact.twig');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function contact($name, $email, $subject, $msg): void
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Validate E-mail Address
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        $transport = Transport::fromDsn('gmail+smtp://alexis.mathiot@gmail.com:mvmwqshtnuybceeh@default');
        $mailer = new Mailer($transport);
        $message = wordwrap($msg, 70);

        $email = (new Email())
            ->from($email)
            ->to('alexis.mathiot@gmail.com')
            ->subject($subject)
            ->html('<p>' . $message . '</p>');

        $mailer->send($email);
        $_SESSION['notification']['notice'] = 'Votre message a été transmis';
        header('location: /contact');
    }
}