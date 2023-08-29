<?php

namespace App\Controller;

use App\Repository\SwipeUpRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('/mine', name: 'admin_list')]
    public function adminList(
        SwipeUpRepository $swipeUpRepository,
    ): Response
    {
        return $this->render('user/admin/list.html.twig', [
            'swipeups' => $swipeUpRepository->findBy(['author' => $this->getUser()]),
        ]);
    }
}
