<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {

    $name = 'Sonny';
    $nickname = 'Sanka';
    $age = 30;
    $hobbies = ['Squash', 'Gaming', 'Tir Sportif'];

        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
            'name' => $name,
            'nickname' => $nickname,
            'age' => $age,
            'hobbies' => $hobbies
        ]);
    }

    #[Route(path: '/articles', name: 'app_articles')]
    public function list(): Response
    {
        return new Response('List of articles');
    }
    #[Route(path: 'article/{id}', name: 'app_article_show')]
    public function show(int $id): Response
    {
        return new Response('Article avec l\'id: ' . $id);
    }
}
