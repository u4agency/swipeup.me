<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\SwipeBackgroundType;
use App\Form\SwipeSectionType;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Repository\WidgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiController extends AbstractController
{
    /**
     * @param SwipeUpRepository $swipeUpRepository
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('check-name', name: '_api_checkname')]
    public function checkName(
        SwipeUpRepository $swipeUpRepository,
        Request           $request
    ): JsonResponse
    {
        $searchTerm = $request->query->get('q');

        $swipe = $swipeUpRepository->findOneBy(['slug' => $searchTerm]);
        return $this->json([
            "response" => !$swipe
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploaderHelper $uploaderHelper
     * @return JsonResponse
     */
    #[Route('upload-swipe', name: '_api_upload-swipe', methods: 'POST')]
    public function uploadSwipe(
        Request                $request,
        EntityManagerInterface $entityManager,
        UploaderHelper         $uploaderHelper,
    ): JsonResponse
    {
        $swipeImage = new SwipeImage();
        $form = $this->createForm(SwipeUploadType::class, $swipeImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $swipeImage->setAlt("");
            $swipeImage->setAuthor($this->getUser());
            $swipeImage->setIsPublic(false);

            $entityManager->persist($swipeImage);
            $entityManager->flush();

            return $this->json([
                "response" => $uploaderHelper->asset($swipeImage, 'backgroundFile'),
            ]);
        }

        return $this->json([
            "response" => "Le formulaire n'est pas valide",
            "errors" => $form->getErrors(true, false)
        ], 400);

    }

//    /**
//     * @param Request $request
//     * @return Response
//     */
//    #[Route('/get-create-section', name: 'api_getcreatesection')]
//    public function getCreateSection(
//        Request                $request,
//    ): Response
//    {
//        return $this->render('_components/_create.section.html.twig');
//    }

    #[Route('upload-background', name: '_api_upload-background')]
    public function uploadBackground(
        Request                $request,
        EntityManagerInterface $entityManager,
        UploaderHelper         $uploaderHelper,
    ): Response
    {
        $swipeImage = new SwipeImage();
        $form = $this->createForm(SwipeBackgroundType::class, $swipeImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $swipeImage->setAlt("");
            $swipeImage->setAuthor($this->getUser());

            $entityManager->persist($swipeImage);
            $entityManager->flush();

            return $this->json([
                "response" => [
                    "id" => $swipeImage->getId(),
                    "url" => $uploaderHelper->asset($swipeImage, 'backgroundFile'),
                ],
            ]);
        }

        return $this->json([
            "error" => "Bad request",
        ]);
    }

    #[Route('create_swipe', name: '_api_swipe_create')]
    public function swipeCreate(
        Request                $request,
        EntityManagerInterface $entityManager,
        WidgetRepository       $widgetRepository,
        SwipeUpRepository      $swipeUpRepository,
    ): Response
    {
        $swipe = new Swipe();

        $section = $this->createForm(SwipeSectionType::class, $swipe)
            ->add('swipeup', HiddenType::class, [
                'mapped' => false,
                'data' => $request->query->get('swipeup')
            ]);
        $section->handleRequest($request);

        if ($section->isSubmitted()) {
            $swipeup = $swipeUpRepository->findOneBy(['slug' => $section->get('swipeup')->getData()]);

            if (!$swipeup || !$this->getUser() || $swipeup->getAuthor() !== $this->getUser()) {
                throw new BadRequestHttpException();
            }

            if (!$section->isValid()) {
                $this->addFlash('error', "Il y a eu une erreur lors de la création du Swipe");
                return $this->redirectToRoute('app_swipeup_edit', [
                    'slug' => $swipeup->getSlug()
                ]);
            }

            $widgetText = $widgetRepository->findOneBy(['name' => 'text']);
            $widgetButton = $widgetRepository->findOneBy(['name' => 'button']);

            if (!empty($section->get('title')->getData())) {
                $widgetBody = new WidgetSwipe();
                $widgetBody->setWidget($widgetText);

                $widgetBodyData = new WidgetData();
                $widgetBodyData->setWidget($widgetText);
                $widgetBodyData->setWidgetSwipe($widgetBody);
                $widgetBodyData->setDataName('text');
                $widgetBodyData->setDataValue($section->get('title')->getData());
                $entityManager->persist($widgetBodyData);

                $swipe->setWidgetBody($widgetBody);
                $entityManager->persist($widgetBody);
            }

            if (!empty($section->get('buttonText')->getData())) {
                $widgetFooter = new WidgetSwipe();
                $widgetFooter->setWidget($widgetButton);

                $widgetFooterTextData = new WidgetData();
                $widgetFooterTextData->setWidget($widgetButton);
                $widgetFooterTextData->setWidgetSwipe($widgetFooter);
                $widgetFooterTextData->setDataName('text');
                $widgetFooterTextData->setDataValue($section->get('buttonText')->getData());
                $entityManager->persist($widgetFooterTextData);

                if (!empty($section->get('buttonHref')->getData())) {
                    $widgetFooterHrefData = new WidgetData();
                    $widgetFooterHrefData->setWidget($widgetButton);
                    $widgetFooterHrefData->setWidgetSwipe($widgetFooter);
                    $widgetFooterHrefData->setDataName('href');
                    $widgetFooterHrefData->setDataValue($section->get('buttonHref')->getData());
                    $entityManager->persist($widgetFooterHrefData);
                }

                $swipe->setWidgetFooter($widgetFooter);
                $entityManager->persist($widgetFooter);
            }

            $swipe->setSwipeup($swipeup);
            try {
                $entityManager->persist($swipe);
                $entityManager->flush();
                $this->addFlash('success', "Le Swipe a bien été créé !");
            } catch (\Exception $exception) {
                $this->addFlash('error', "Une erreur est survenue lors de la modification du SwipeUp !");
            }

            return $this->redirectToRoute('app_swipeup_edit', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('_components/create/form_create.html.twig', [
            'form' => $section->createView(),
        ]);
    }
}