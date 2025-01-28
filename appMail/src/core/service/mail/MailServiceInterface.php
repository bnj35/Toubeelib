<?php

namespace toubeelib\core\service\mail;

interface MailServiceInterface {
    public function sendEmail($recipientEmail, $subject, $details);
}
