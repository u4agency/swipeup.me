<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Form\SwipeSectionType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    #[Route('/s{id}_{newValue}', name: 'sequence', requirements: ['newValue' => '\d+'], defaults: ['newValue' => 0, 'id' => 0], methods: ['PATCH'])]
    public function sequence(
        Swipe                  $swipe,
        SwipeRepository        $swipeRepository,
        EntityManagerInterface $entityManager,
        int                    $newValue,
    ): Response
    {
        if (
            (
                !$this->getUser() ||
                $swipe->getSwipeup()->getAuthor() !== $this->getUser() ||
                $this->getUser()->getSwipeUps()->count() < 1
            ) &&
            !$this->isGranted('ROLE_ADMIN')
        ) {
            throw new BadRequestHttpException();
        }


        try {
            // Réarranger les autres cases
            $casesToRearrange = $swipe->getSequence() < $newValue ? $swipeRepository->getAfterOrder($swipe->getSwipeup(), $swipe->getSequence(), $newValue) : $swipeRepository->getBeforeOrder($swipe->getSwipeup(), $swipe->getSequence(), $newValue);

            foreach ($casesToRearrange as $caseToRearrange) {
                if ($swipe->getSequence() < $newValue) $caseToRearrange->setSequence($caseToRearrange->getSequence() - 1);
                else $caseToRearrange->setSequence($caseToRearrange->getSequence() + 1);

                $entityManager->persist($caseToRearrange);
            }

            // Mettre à jour la case déplacée
            $swipe->setSequence($newValue);

            $entityManager->persist($swipe);
            $entityManager->flush();
        } catch (Exception $e) {
            $entityManager->rollback();
            return new Response('Une erreur est survenue.', 500);
        }

        return new Response();
    }
}
