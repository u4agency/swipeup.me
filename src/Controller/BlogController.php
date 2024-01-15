<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Repository\PostsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'app_blog')]
    public function blog(
        PostsRepository    $postsRepository,
        PaginatorInterface $paginator,
        Request            $request
    ): Response
    {
        if ($this->getUser() && $this->isGranted("ROLE_ADMIN")) {
            $status = ['published', 'pending'];
        } else {
            $status = ['published'];
        }

        $news = $postsRepository->findBy(['status' => $status], ['createdAt' => 'desc']);

        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('blog/index.html.twig', [
            'news' => $pagination,
        ]);
    }

    #[Route('/{slug}', name: 'app_article')]
    public function article(
        Posts $post,
    ): Response
    {
        return $this->render('blog/article.html.twig', [
            'article' => $post,
        ]);
    }
}
