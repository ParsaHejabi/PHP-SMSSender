<?php
namespace App\Controller;

use App\Entity\Log;
use App\Message\SmsMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsController extends AbstractController
{
    public function send(MessageBusInterface $bus)
    {
        $request = Request::createFromGlobals();
        $number = $request->get('number');
        $body = $request->get('body');

        if(is_null($number) || preg_match('/09[0-9]{9}/', $number) === FALSE) {
            return new JsonResponse((['error' => 'Invalid phone number']), 422);
        }
        if(is_null($body) || strlen($body) > 190) {
            return new JsonResponse((['error' => 'Invalid body text']), 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $log = new Log();
        $log->setNumber($number);
        $log->setBody($body);
        $log->setCreatedAt(new \DateTime());

        $entityManager->persist($log);
        $entityManager->flush();

        $message = new SmsMessage($number, $body, $log->getId());

        $bus->dispatch($message);

        return new JsonResponse([
            'number' => $message->getNumber(),
            'body' => $message->getBody(),
            'status' => 'received'
        ], 200);
    }
}