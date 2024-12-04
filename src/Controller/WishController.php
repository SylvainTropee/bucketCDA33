<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\CommentType;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(WishRepository $wishRepository): Response
    {

        $wishes = $wishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '[0-9]+'], methods: ['GET', 'POST'])]
    public function detail(
        #[MapEntity] Wish      $wish,
        Request                $request,
        EntityManagerInterface $entityManager): Response
    {

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            $comment->setWish($wish);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
            'commentForm' => $commentForm
        ]);
    }


    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '[0-9]+'], methods: ['GET'])]
    public function delete(#[MapEntity] Wish $wish, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($wish);
        $entityManager->flush();

        return $this->redirectToRoute('wish_list');
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '[0-9]+'], methods: ['GET', 'POST'])]
//    #[IsGranted(
//        attribute: new Expression('user === subject or is_granted("ROLE_ADMIN")'),
//        subject: new Expression('args["wish"].getUser()')
//    )]

    #[IsGranted('WISH_EDIT', 'wish')]
    public function update(#[MapEntity] Wish $wish, Request $request, EntityManagerInterface $entityManager): Response
    {

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Wish added !');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }


        return $this->render('wish/update.html.twig', [
            'wishForm' => $wishForm,
        ]);
    }


    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
//    #[IsGranted('ROLE_USER')]
    public function create(
        Request                $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator
    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            $wish->setUser($this->getUser());

            $wish->setTitle($censurator->purify($wish->getTitle()));
            $wish->setDescription($censurator->purify($wish->getDescription()));

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Wish added !');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }
}
