<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Form\SwipeBackgroundType;
use App\Form\SwipeSectionType;
use App\Form\SwipeUpCreateType;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Service\ColorContrast;
use App\Service\Status;
use Doctrine\ORM\EntityManagerInterface;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/')]
class SwipeController extends AbstractController
{
    #[Route('@{slug}', name: 'app_swipeup_single', priority: -1)]
    public function singleSwipeUp(
        SwipeUp         $swipeup,
        UploaderHelper  $uploaderHelper,
        KernelInterface $appKernel,
    ): Response
    {
        if ($swipeup->getStatus() === Status::DELETED && !$this->isGranted('ROLE_ADMIN')) throw $this->createNotFoundException();

        if ($swipeup->getStatus() === Status::PRIVATE && $this->getUser() !== $swipeup->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Le SwipeUp demandé est innaccessible');
            return $this->redirectToRoute('app_login');
        }

        $colors = [];
        $colorsNumber = 4;
        $extractedColors = $swipeup->getLogoName() ? (new ColorExtractor(Palette::fromFilename($appKernel->getProjectDir() . '/public/' . $uploaderHelper->asset($swipeup, 'logoFile'))))->extract($colorsNumber) : null;
        for ($i = 0; $i < $colorsNumber; $i++) $colors[] = Color::fromIntToHex($extractedColors[$i] ?? 0);

        return $this->render('swipe/single.html.twig', [
            'swipeup' => $swipeup,
            'colors' => $colors,
            'lightText' => ColorContrast::getBool($colors[0]),
        ]);
    }

    #[Route('swipeup', name: 'app_swipe_create')]
    public function createSwipe(
        Request                $request,
        EntityManagerInterface $entityManager,
        SwipeUpRepository      $swipeUpRepository,
    ): Response
    {
        $swipeup = new SwipeUp();

        if ($request->query->has('slug')) {
            if (!$this->getUser()) return $this->redirectToRoute('app_register', ['swipeup_create' => $request->query->get('slug')]);

            $swipeup->setSlug($request->query->get('slug'));
        }

        $form = $this->createForm(SwipeUpCreateType::class, $swipeup);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $request->query->has('slug')) {
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($swipeup->getSlug());

            if (!$this->getUser()) return $this->redirectToRoute('app_register', ['swipeup_create' => $slug]);

            if ($swipeUpRepository->countAllExceptDeleted($this->getUser()) >= 1 && !$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Vous avez atteint la limite de création de SwipeUp !');
                return $this->redirectToRoute('app_user_admin_list');
            }

            $swipeup->setSlug($slug);
            $swipeup->setTitle("SwipeUp de " . $this->getUser());
            $swipeup->setDescription("Ceci est le SwipeUP de " . $this->getUser() . " !");
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
