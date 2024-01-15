<?php

namespace App\Controller;

use App\Entity\URLShortener;
use App\Form\URLShortenerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class URLShortenerController extends AbstractController
{
    #[Route('~{slug}', name: 'app_url_shortener')]
    public function singleUrlShortener(
        URLShortener $urlShortener,
    ): Response
    {
        return $this->redirect($urlShortener->getLink());
    }

    #[Route('/shortlink', name: 'app_urlshortener_createurlshortener')]
    public function createUrlShortener(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $urlShortener = new URLShortener();

        $form = $this->createForm(URLShortenerType::class, $urlShortener);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $urlShortener->setSlug(uniqid());
            $urlShortener->setCreatedBy($this->getUser() ? $this->getUser() : null);

            $entityManager->persist($urlShortener);
            $entityManager->flush();

            return $this->render('url_shortener/render.html.twig', [
                'urlShortener' => $urlShortener,
            ]);
        }

        return $this->render('url_shortener/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/shortlink/claim/{id}', name: 'app_urlshortener_claimurlshortener')]
    public function claimUrlShortener(
        URLShortener $urlShortener,
    ): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('app_register');

        if ($urlShortener->getCreatedBy()) {
            $this->addFlash('error', 'Ce lien a déjà été revendiqué');
            return $this->redirectToRoute('app_user_admin_list');
        }

        $urlShortener->setCreatedBy($this->getUser());
        $this->addFlash('success', 'Le lien a bien été revendiqué');

        return $this->redirectToRoute('app_user_admin_list');
    }
}
