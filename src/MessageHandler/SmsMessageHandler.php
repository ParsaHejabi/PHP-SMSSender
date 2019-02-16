<?php
/**
 * Created by PhpStorm.
 * User: Parsa
 * Date: 2/15/2019
 * Time: 3:28 PM
 */

namespace App\MessageHandler;

use App\Entity\Log;
use App\Helper\ApiConnection;
use App\Message\SmsMessage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SmsMessageHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function __invoke(SmsMessage $message)
    {
        $log = $this->entityManager->getRepository(Log::class)->find($message->getLogId());

        $api1_success = true;
        $api2_success = true;

        try {
            $api1 = new ApiConnection('http://localhost:81');
            $api1->sendSms($message->getNumber(), $message->getBody());
            $log->setApiUsed(1);
        } catch (\Exception $e) {
            $api1_success = false;
            echo 'API 1 Error: ' . $e->getMessage() . PHP_EOL;
        } finally {
            $log->setAttempts($log->getAttempts()+1);
        }
        if(!$api1_success) {
            try {
                $api2 = new ApiConnection('http://localhost:82');
                $api2->sendSms($message->getNumber(), $message->getBody());
                $log->setApiUsed(2);
            } catch (\Exception $e) {
                $api2_success = false;
                echo 'API 2 Error: ' . $e->getMessage() . PHP_EOL;
            } finally {
                $log->setAttempts($log->getAttempts()+1);
            }
        }

        if(!$api1_success && !$api2_success) {
            $log->setStatus(2);
        }
        else {
            $log->setStatus(1);
        }

        $this->entityManager->flush();

        echo 'Message with log id ' . $log->getId() . " processed.\n";
    }
}