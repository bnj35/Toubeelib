<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/mail.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
$channel = $connection->channel();

$channel->queue_declare('notification_queue', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function($msg) {
    $data = json_decode($msg->body, true);
    echo " [x] Decoded details =>", " Praticien Id : ",$data['recipient']['praticienId']," Patient ID : ", $data['recipient']['patientId'], "\n";

    // if (json_last_error() === JSON_ERROR_NONE) {
    //     sendEmail($data['recipient']['email'], $data['event'], $data['details']);
    // } else {
    //     echo "Error decoding JSON message\n";
    // }
};

$channel->basic_consume('notification_queue', '', false, true, false, false, $callback);

while($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();