<?php

namespace App\Controller;

use App\Repository\PagesRepository;
use App\Repository\PostsRepository;
use App\Repository\SwipeUpRepository;
use App\Service\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class SitemapController extends AbstractController
{
    #[Route('robots.txt', name: 'seo_robots', defaults: ['_format' => 'txt'])]
    public function robots(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        return new Response(
            $this->renderView('sitemap/robots.html.twig', [
                'hostname' => $hostname
            ]),
            200,
            [
                'Content-Type' => 'text/plain'
            ]
        );
    }

    #[Route('sitemap.xml', name: 'sitemap_index', defaults: ['_format' => 'xml'])]
    public function sitemapIndex(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = [
            'loc' => $this->generateUrl('sitemap', ['name' => 'pages']),
        ];
        $urls[] = [
            'loc' => $this->generateUrl('sitemap', ['name' => 'swipeup']),
        ];
        $urls[] = [
            'loc' => $this->generateUrl('sitemap', ['name' => 'posts']),
        ];

        return new Response(
            $this->renderView('sitemap/sitemap_index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200,
            [
                'Content-Type' => 'text/xml'
            ]
        );
    }

    #[Route('sitemap_{name}.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function sitemap(
        Request           $request,
        UploaderHelper    $uploaderHelper,
        PagesRepository   $pageRepository,
        PostsRepository   $postRepository,
        SwipeUpRepository $swipeUpRepository,

        string            $name,
    ): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        if ($name === 'pages') {
            $urls[] = [
                'loc' => $this->generateUrl('app_homepage'),
                'priority' => 1
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_swipe_create'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_urlshortener_createurlshortener'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_qrcode_createqrcode'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_swipe_randomswipeup'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_login'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_register'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_about'),
                'priority' => 0.9
            ];
            $urls[] = [
                'loc' => $this->generateUrl('app_blog'),
                'priority' => 0.9
            ];

            foreach ($pageRepository->findAll() as $page) {
                $urls[] = [
                    'loc' => $this->generateUrl('app_page', ['slug' => $page->getSlug()]),
                    'lastmod' => $page->getUpdatedAt()->format('c'),
                    'changefreq' => 'weekly',
                    'priority' => 0.9,
                ];
            }
        }
        if ($name === 'swipeup') {
            foreach ($swipeUpRepository->findBy(['status' => Status::PUBLIC]) as $swipeUp) {
                $urls[] = [
                    'loc' => $this->generateUrl('app_swipeup_single', ['slug' => $swipeUp->getSlug()]),
                    'lastmod' => $swipeUp->getUpdatedAt()->format('c'),
                    'changefreq' => 'daily',
                    'priority' => 0.8,
                    'image' => [
                        [
                            'loc' => $uploaderHelper->asset($swipeUp, 'logoFile'),
                            'title' => $swipeUp->getTitle()
                        ]
                    ]
                ];
            }
        }
        if ($name === 'posts') {
            foreach ($postRepository->findBy(['status' => Status::PUBLIC]) as $post) {
                $urls[] = [
                    'loc' => $this->generateUrl('app_article', ['slug' => $post->getSlug()]),
                    'lastmod' => $post->getUpdatedAt()->format('c'),
                    'changefreq' => 'daily',
                    'priority' => 0.7,
                    'image' => [
                        [
                            'loc' => $uploaderHelper->asset($post, 'imageFile'),
                            'title' => $post->getSeoTitle()
                        ]
                    ]
                ];
            }
        }

        return new Response(
            $this->renderView('sitemap/sitemap.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200,
            [
                'Content-Type' => 'text/xml'
            ]
        );
    }
}