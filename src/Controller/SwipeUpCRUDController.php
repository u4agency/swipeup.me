<?php

namespace App\Controller;

use App\Entity\SwipeUp;
use App\Service\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/swipeup', name: '_api_swipeup_')]
class SwipeUpCRUDController extends AbstractController
{
    #[Route('/d{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SwipeUp $swipeup, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $swipeup->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du SwipeUp.');
            return $this->redirectToRoute('app_user_swipeup_settings', ['slug' => $swipeup->getSlug()]);
        }

        try {
            $swipeup->setStatus(Status::DELETED);
            $entityManager->persist($swipeup);
            $entityManager->flush();

            $this->addFlash('success', 'Le SwipeUp a bien été supprimé.');
            return $this->redirectToRoute('app_user_admin_list');
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du SwipeUp.');
            return $this->redirectToRoute('app_user_swipeup_settings', ['slug' => $swipeup->getSlug()]);
        }
    }
}
