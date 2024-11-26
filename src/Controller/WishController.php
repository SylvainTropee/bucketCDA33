<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig');
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '[0-9]+'], methods: ['GET'])]
    public function detail(int $id): Response
    {

        dump($id);
        return $this->render('wish/detail.html.twig');
    }
}
