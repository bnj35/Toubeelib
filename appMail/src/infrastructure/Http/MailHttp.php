<?php
namespace toubeelib\infrastructure\Http;

require 'vendor/autoload.php';

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
use toubeelib\core\service\mail\MailService;


        try {
            $mailService = new MailService();

            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
            $channel = $connection->channel();
            $channel->exchange_declare('notification_exchange', 'direct', false, true, false);
            $channel->queue_declare('notification_queue', false, true, false, false, false);
            $channel->queue_bind('notification_queue', 'notification_exchange');

            $callback = function($msg) use ($mailService) {
                $data = json_decode($msg->body, true);
                echo " [x] Decoded details =>", " Praticien Id : ",$data['recipient']['praticienId']," Patient ID : ", $data['recipient']['patientId'],$data['recipient']['praticienMail'], "\n";
                
                $praticienMail = $data['recipient']['praticienMail'];
                $patientMail = $data['recipient']['patientMail'];
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    $mailService->sendEmail($praticienMail, $data['event'], $data['details']);
                    $mailService->sendEmail($patientMail, $data['event'], $data['details']);
                    echo " [x] Sent email to Praticien and Patient\n";
                } else {
                    echo "Error decoding JSON message\n";
                }
            };

            $channel->basic_consume('notification_queue', '', false, false, false, false, $callback);
        
            echo " [*] Waiting for messages. To exit press CTRL+C\n";

            while($channel->is_consuming()) {
                $channel->wait();
            }

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            echo "Error: ", $e->getMessage(), "\n";
        }

