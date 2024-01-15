<?php

namespace App\Controller;

use App\Entity\URLShortener;
use App\Form\QRCodeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QRCodeController extends AbstractController
{
    #[Route('/qrcode', name: 'app_qrcode_createqrcode')]
    public function createQRCode(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $urlShortener = new URLShortener();

        $form = $this->createForm(QRCodeType::class, $urlShortener);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link = $urlShortener->getLink();

            if ($form->get('urlshort')->getData()) {
                $urlShortener->setSlug(uniqid());
                $urlShortener->setCreatedBy($this->getUser() ? $this->getUser() : null);

                $link = $this->generateUrl('app_url_shortener', ['slug' => $urlShortener->getSlug()]);

                $entityManager->persist($urlShortener);
                $entityManager->flush();
            }

            return $this->render('qr_code/render.html.twig', [
                'qr' => $link,
                'urlShortener' => $form->get('urlshort')->getData() ? $urlShortener : null,
                'blur' => false,
            ]);
        }

        return $this->render('qr_code/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
