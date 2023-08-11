<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Form\SwipeType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/swipe', name: '_api_swipe_')]
class SwipeCRUDController extends AbstractController
{
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Swipe $swipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SwipeType::class, $swipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_swipe_c_r_u_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('swipe_crud/edit.html.twig', [
            'swipe' => $swipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Swipe $swipe, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $swipe->getId(), $request->request->get('_token'))) {
            return new Response('Le formulaire est invalide.', Response::HTTP_BAD_REQUEST);
        }

        try {
            $entityManager->remove($swipe);
            $entityManager->flush();
        } catch (\Exception $exception) {
            return new Response('Une erreur est survenue.', Response::HTTP_BAD_REQUEST);
        }

        return new Response();
    }
}
