<?php

namespace App\Controller;

use App\Repository\SwipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class SitemapController extends AbstractController
{
    /**
     * @Route("sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     * @param Request $request
     * @param SwipeRepository $swipeRepository
     * @param UploaderHelper $uploaderHelper
     * @return Response
     */
    public function sitemap(
        Request         $request,
        SwipeRepository $swipeRepository,
        UploaderHelper  $uploaderHelper,
    ): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = [
            'loc' => $this->generateUrl('app_homepage'),
            'priority' => 1
        ];
        $urls[] = [
            'loc' => $this->generateUrl('app_login'),
            'priority' => 0.9
        ];
        $urls[] = [
            'loc' => $this->generateUrl('app_register'),
            'priority' => 0.9
        ];

        foreach ($swipeRepository->findBy(['status' => 'public']) as $swipe) {
            $urls[] = [
                'loc' => $this->generateUrl('app_swipe_single', ['slug' => $swipe->getSlug()]),
                'lastmod' => $swipe->getUpdatedAt()->format('c'),
                'changefreq' => 'daily',
                'priority' => 0.8,
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ])
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

    /**
     * @Route("robots.txt", name="robots", defaults={"_format"="txt"})
     * @param Request $request
     * @return Response
     */
    public function robots(Request $request): Response
    {
        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();

        $response = new Response(
            $this->renderView('sitemap/robots.html.twig', [
                'hostname' => $hostname
            ])
        );

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}