<?php

namespace App\Controller;

use App\Entity\SwipeImage;
use App\Form\SwipeBackgroundType;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}