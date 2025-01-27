<?php

namespace toubeelib\core\service\mail;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MailService implements MailServiceInterface {

    public function __construct(
    ) {
        // Constructor can be used to initialize properties if needed
    }

    public function sendEmail($recipientEmail, $subject, $details) {
        if ($recipientEmail === null) {
            echo "Error: recipient email is null\n";
            return;
        }

        $email = (new Email())
            ->from('email@example.com')
            ->to($recipientEmail)
            ->subject($subject)
            ->text('Details: ' . $details);

        $transport = Transport::fromDsn('smtp://mailcatcher:1025');
        $mailer = new Mailer($transport);
        $mailer->send($email);

        echo "Email sent to ", $recipientEmail, "\n";
    }
}
