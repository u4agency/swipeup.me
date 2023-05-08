<?php

namespace App\Controller;

use App\Entity\SwipeImage;
use App\Form\SwipeUploadType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiController extends AbstractController
{
    /**
     * @param SwipeRepository $swipeRepository
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/check-name', name: 'api_checkname')]
    public function checkName(
        SwipeRepository $swipeRepository,
        Request         $request
    ): JsonResponse
    {
        $searchTerm = $request->query->get('q');

        $swipe = $swipeRepository->findOneBy(['slug' => $searchTerm]);
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
    #[Route('/upload-swipe', name: 'api_uploadswipe', methods: 'POST')]
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
}