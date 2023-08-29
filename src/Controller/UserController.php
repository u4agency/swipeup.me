<?php

namespace App\Controller;

use App\Entity\SwipeUp;
use App\Form\SwipeUpEditType;
use App\Repository\SwipeUpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/swipeup/@{slug}', name: 'swipeup_edit')]
    public function editSwipeUp(
        SwipeUp                $swipeup,
        EntityManagerInterface $entityManager,
        Request                $request,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
            return $this->redirectToRoute('app_swipeup_single', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        $form = $this->createForm(SwipeUpEditType::class, $swipeup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($swipeup);
                $entityManager->flush();
            } catch (\Exception $exception) {
                $this->addFlash('error', "Une erreur est survenue lors de la modification du SwipeUp !");
            }

            return $this->redirectToRoute('app_user_swipeup_edit', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('swipe/edit.html.twig', [
            'swipeup' => $swipeup,
            'form' => $form->createView(),
        ]);
    }
}
