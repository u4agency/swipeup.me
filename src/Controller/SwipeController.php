<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\NewsletterType;
use App\Form\SwipeBackgroundType;
use App\Form\SwipeSectionType;
use App\Form\SwipeUpCreateType;
use App\Form\SwipeUpEditType;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Repository\WidgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/')]
class SwipeController extends AbstractController
{
    #[Route('swipeup', name: 'app_swipeup')]
    public function allSwipeUps(
        SwipeRepository $swipeRepository,
    ): Response
    {
        return $this->render('swipe/index.html.twig', [
            'swipes' => $swipeRepository->findAll()
        ]);
    }

    #[Route('@{slug}', name: 'app_swipeup_single', priority: -1)]
    public function singleSwipeUp(SwipeUp $swipeup): Response
    {
        if ($swipeup->getStatus() === 'private' && $this->getUser() !== $swipeup->getAuthor()) {
            $this->addFlash('error', 'Le SwipeUp demandé est innaccessible');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('swipe/single.html.twig', [
            'swipeup' => $swipeup,
        ]);
    }

    #[Route('create', name: 'app_swipe_create')]
    public function createSwipe(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $swipeup = new SwipeUp();
        $form = $this->createForm(SwipeUpCreateType::class, $swipeup);
        $form->handleRequest($request);

        if ($request->query->has('slug')) {
            $swipeup->setSlug($request->query->get('slug'));
        }

        if (($form->isSubmitted() && $form->isValid()) || $request->query->has('slug')) {
            if ($this->getUser()->getSwipeUps()->count() >= 1 && !$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Vous avez atteint la limite de création de SwipeUp !');
                return $this->redirectToRoute('app_homepage');
            }

            $swipeup->setTitle("SwipeUp de " . $this->getUser());
            $swipeup->setDescription("Ceci est le SwipeUP de " . $this->getUser() . " !");
            $swipeup->setStatus("public");
            $swipeup->setAuthor($this->getUser());

            $entityManager->persist($swipeup);

            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Le nom de SwipeUp est déjà utilisé !');
                return $this->redirectToRoute('app_swipe_create');
            }

            return $this->redirectToRoute('app_user_swipeup_edit', ['slug' => $swipeup->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('swipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('random', name: 'app_swipe_randomswipeup')]
    public function randomSwipeUp(
        SwipeUpRepository $swipeupRepository,
    ): RedirectResponse
    {
        return $this->redirectToRoute('app_swipeup_single', [
            'slug' => $swipeupRepository->randomRow()[0]['slug']
        ]);
    }

    public function _sectionCreate(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $swipe = new Swipe();
        $swipeForm = $this->createForm(SwipeSectionType::class, $swipe);
        $swipeForm->handleRequest($request);

        $swipeImage = new SwipeImage();
        $backgroundForm = $this->createForm(SwipeBackgroundType::class, $swipeImage);

        if ($swipeForm->isSubmitted() && $swipeForm->isValid()) {
            $entityManager->persist($swipe);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new Response(null, 204);
            }
        }

        return $this->render('swipe/_create_section.html.twig', [
            'swipeForm' => $swipeForm->createView(),
            'backgroundForm' => $backgroundForm->createView(),
            'sectionCount' => $request->query->get('sectionCount'),
        ]);
    }
}
