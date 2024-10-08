<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostsRepository;
use App\Service\Status;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'app_category')]
    public function blog(
        CategoryRepository $categoryRepository,
        PostsRepository    $postsRepository,
        PaginatorInterface $paginator,
        Request            $request,
                           $slug
    ): Response
    {
        if ($this->getUser() && $this->isGranted("ROLE_ADMIN")) {
            $status = [Status::PUBLIC, Status::PENDING];
        } else {
            $status = [Status::PUBLIC];
        }

        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        $posts = $postsRepository->findByCategory($category);

        $pagination = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'posts' => $pagination,
        ]);
    }
}
