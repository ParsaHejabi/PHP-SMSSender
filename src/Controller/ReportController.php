<?php
namespace App\Controller;
header("Access-Control-Allow-Origin: http://localhost");

use App\Entity\Log;
use App\Message\SmsMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class ReportController extends AbstractController
{
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Log::class);

        $request = Request::createFromGlobals();
        $number = $request->get('number');

        $countQuery = $entityManager->createQuery('SELECT COUNT(u.id) FROM App\Entity\Log u');
        $firstUsageQuery = $entityManager->createQuery("SELECT COUNT(u.id) FROM App\Entity\Log u WHERE u.api_used=1");
        $secondUsageQuery = $entityManager->createQuery("SELECT COUNT(u.id) FROM App\Entity\Log u WHERE u.api_used=2");
        $firstErrQuery = $entityManager->createQuery("SELECT COUNT(u.id) FROM App\Entity\Log u WHERE u.attempts>=1 AND u.status=2");
        $secondErrQuery = $entityManager->createQuery("SELECT COUNT(u.id) FROM App\Entity\Log u WHERE u.attempts=2 AND u.status=2");
        $count = $countQuery->getSingleScalarResult();
        $firstUsage = $firstUsageQuery->getSingleScalarResult();
        $secondUsage = $secondUsageQuery->getSingleScalarResult();
        $firstErr = $firstErrQuery->getSingleScalarResult();
        $secondErr = $secondErrQuery->getSingleScalarResult();

        $topTenQuery = $entityManager->createQuery("SELECT u.number,COUNT(u.id) AS smsCount FROM App\Entity\Log u GROUP BY u.number ORDER BY COUNT(u.id) DESC");
        $topTenQuery->setMaxResults(10);
        $topTen = $topTenQuery->getResult();

        if(!is_null($number) && preg_match('/9891[0-9]{8}/', $number)) {
            $messages = $repository->findBy([
                'number' => $number
            ]);
        }
        else {
            $messages = $repository->findBy([], ['created_at' => 'DESC'], $count);
        }

        return new JsonResponse([
            'list' => $messages,
            'count' => $count,
            'firstUsage' => $firstUsage/$count,
            'secondUsage' => $secondUsage/$count,
            'firstError' => $firstErr/$count,
            'secondError' => $secondErr/$count,
            'topTen' => $topTen
        ], 200);
    }
}