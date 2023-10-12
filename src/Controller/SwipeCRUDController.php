<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Form\SwipeSectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if ((!$this->getUser() || $swipe->getSwipeup()->getAuthor() !== $this->getUser() || $this->getUser()->getSwipeUps()->count() < 1) && !$this->isGranted('ROLE_ADMIN')) {
            throw new BadRequestHttpException();
        }

        $section = $this->createForm(SwipeSectionType::class, $swipe, [
            'widgetBodyValue' => $swipe->getWidgetBody()?->getWidget()->getName(),
            'widgetFooterValue' => $swipe->getWidgetFooter()?->getWidget()->getName(),
        ]);

        $section->handleRequest($request);

        if ($section->isSubmitted()) {
            $swipeup = $swipe->getSwipeup();

            if ((!$swipeup || !$this->getUser() || $swipeup->getAuthor() !== $this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
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

        return $this->render('_components/create/form_edit.html.twig', [
            'form' => $section->createView(),
            'action' => $this->generateUrl('_api_swipe_edit', ['id' => $swipe->getId()]),
            'widgetBodyValue' => $swipe->getWidgetBody()?->getWidget()->getName(),
            'widgetFooterValue' => $swipe->getWidgetFooter()?->getWidget()->getName(),
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
