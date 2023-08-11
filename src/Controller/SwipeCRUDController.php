<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Form\SwipeSectionType;
use App\Form\SwipeType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/swipe', name: '_api_swipe_')]
class SwipeCRUDController extends AbstractController
{
    #[Route('/e{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Swipe                  $swipe,
        EntityManagerInterface $entityManager
    ): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY') || $swipe->getSwipeup()->getAuthor() !== $this->getUser() || $this->getUser()->getSwipeUps()->count() < 1) {
            throw new BadRequestHttpException();
        }

        $section = $this->createForm(SwipeSectionType::class, $swipe)
            ->add('swipeup', HiddenType::class, [
                'mapped' => false,
                'data' => $request->query->get('swipeup')
            ]);
        $section->handleRequest($request);

        if ($section->isSubmitted()) {
            $swipeup = $swipe->getSwipeup();

            if (!$swipeup || !$this->getUser() || $swipeup->getAuthor() !== $this->getUser()) {
                throw new BadRequestHttpException();
            }

            if (!$section->isValid()) {
                return new Response("Le formulaire n'est pas valide.", 400);
            }

            $swipe->setSwipeup($swipeup);
            try {
                $entityManager->persist($swipe);
                $entityManager->flush();
            } catch (\Exception $exception) {
                return new Response('Une erreur est survenue.', 500);
            }

            return new Response('Le Swipe a bien été créé !');
        }

        return $this->render('_components/create/form/_form.html.twig', [
            'form' => $section->createView(),
            'action' => $this->generateUrl('_api_swipe_edit', ['id' => $swipe->getId()]),
            'buttonText' => "Modifier le Swipe",
        ]);
    }

    #[Route('/d{id}', name: 'delete', methods: ['POST'])]
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
