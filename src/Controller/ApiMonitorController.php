<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/monitor', name: '_monitor_')]
class ApiMonitorController extends AbstractController
{
    #[Route('/website', name: 'website')]
    public function website(): Response
    {
        return new Response("OK");
    }

    #[Route('/database', name: 'database')]
    public function database(
        EntityManagerInterface $entityManager
    ): Response
    {
        try {
            $entityManager->getConnection()->connect();
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response("OK");
    }
}