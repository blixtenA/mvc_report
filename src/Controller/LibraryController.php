<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'book_create')]
    public function createBook(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_library');
        }

        return $this->render('library/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/library/readone/{id}', name: 'book_read_one', requirements: ['id' => '\d+'])]
    public function readOne(int $id, ManagerRegistry $doctrine): Response
    {
        $bookRepository = $doctrine->getRepository(Book::class);
        $book = $bookRepository->find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        return $this->render('library/read_one.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/readmany', name: 'book_read_many')]
    public function readMany(ManagerRegistry $doctrine): Response
    {
        $bookRepository = $doctrine->getRepository(Book::class);
        $books = $bookRepository->findAll();

        return $this->render('library/read_many.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/update/{id}', name: 'book_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $bookRepository = $doctrine->getRepository(Book::class);
        $book = $bookRepository->find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('book_read_many');
        }

        return $this->render('library/update.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'book_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $bookRepository = $doctrine->getRepository(Book::class);
        $book = $bookRepository->find($id);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_read_many');
    }

}
