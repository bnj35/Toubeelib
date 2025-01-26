<?php

namespace toubeelib\application\class;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpBadRequestException;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use toubeelib\core\service\mail\MailServiceInterface;
use toubeelib\core\services\praticien\PraticienInfoServiceInterface;
use toubeelib\core\services\patient\PatientInfoServiceInterface;

class ClassMail {
    private $praticienInfoService;
    private $patientInfoService;
    private $mailService;

    public function __construct(
        PraticienInfoServiceInterface $praticienInfoService,
        PatientInfoServiceInterface $patientInfoService,
        MailServiceInterface $mailService
    ) {
        $this->praticienInfoService = $praticienInfoService;
        $this->patientInfoService = $patientInfoService;
        $this->mailService = $mailService;
    }

    public function __invoke() {
        try {
            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
            $channel = $connection->channel();

            $channel->queue_declare('notification_queue', false, true, false, false);

            echo " [*] Waiting for messages. To exit press CTRL+C\n";

            $callback = function($msg) {
                $data = json_decode($msg->body, true);
                echo " [x] Decoded details =>", " Praticien Id : ",$data['recipient']['praticienId']," Patient ID : ", $data['recipient']['patientId'], "\n";
                $praticien = $this->praticienInfoService->getPraticienById($data['recipient']['praticienId']);
                $patient = $this->patientInfoService->getPatientById($data['recipient']['patientId']);

                $praticienMail = $praticien['email'];
                $patientMail = $patient['email'];
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->mailService->sendEmail($praticienMail, $data['event'], $data['details']);
                    $this->mailService->sendEmail($patientMail, $data['event'], $data['details']);
                } else {
                    echo "Error decoding JSON message\n";
                }
            };

            $channel->basic_consume('notification_queue', '', false, true, false, false, $callback);

            while($channel->is_consuming()) {
                $channel->wait();
            }

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            echo "Error: ", $e->getMessage(), "\n";
        }
    }
}

