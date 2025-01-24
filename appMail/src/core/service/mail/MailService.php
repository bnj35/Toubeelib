<?php

namespace toubeelib\core\service\mail;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;
use toubeelib\core\services\patient\PatientInfoServiceInterface;

class MailService implements MailServiceInterface {
    private $praticienInfoService;
    private $patientInfoService;

    public function __construct(
        PraticienInfoServiceInterface $praticienInfoService,
        PatientInfoServiceInterface $patientInfoService
    ) {
        $this->praticienInfoService = $praticienInfoService;
        $this->patientInfoService = $patientInfoService;
    }

    public function sendEmail($recipientEmail, $subject, $details) {
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
