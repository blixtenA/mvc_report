<?php

namespace App\Tests\Controller;

use App\Controller\LibraryController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LibraryControllerTest extends TestCase
{
    public function testIndex(): void
    {
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->willReturn($response);

        $actualResponse = $controller->index($request);

        $this->assertSame($response, $actualResponse);
    }

    public function testCreateBook(): void
    {
        $request = $this->createMock(Request::class);
        $doctrine = $this->createMock(ManagerRegistry::class);
        $form = $this->createMock(FormInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $response = $this->createMock(RedirectResponse::class);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['createForm', 'render', 'redirectToRoute'])
            ->getMock();

        $controller->expects($this->once())
            ->method('createForm')
            ->willReturn($form);

        $form->expects($this->once())
            ->method('handleRequest');

        $form->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $doctrine->expects($this->once())
            ->method('getManager')
            ->willReturn($entityManager);

        $entityManager->expects($this->once())
            ->method('persist');

        $entityManager->expects($this->once())
            ->method('flush');

        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('book_read_many')
            ->willReturn($response);

        $actualResponse = $controller->createBook($request, $doctrine);

        $this->assertSame($response, $actualResponse);
    }

    public function testReadOne(): void
    {
        $id = 1;
        $doctrine = $this->createMock(ManagerRegistry::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $book = $this->createMock(Book::class);
        $response = $this->createMock(Response::class);

        $doctrine->expects($this->once())
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($bookRepository);

        $bookRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($book);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->with('library/read_one.html.twig', ['book' => $book])
            ->willReturn($response);
        
        $actualResponse = $controller->readOne($id, $doctrine);

        $this->assertSame($response, $actualResponse);
    }

    public function testReadOneThrowsNotFoundException(): void
    {
        $id = 1;
        $doctrine = $this->createMock(ManagerRegistry::class);
        $bookRepository = $this->createMock(BookRepository::class);
        
        $doctrine->expects($this->once())
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($bookRepository);

        $bookRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->never())
            ->method('render');
        
        $this->expectException(NotFoundHttpException::class);
        $controller->readOne($id, $doctrine);
    }

    public function testReadMany(): void
    {
        $doctrine = $this->createMock(ManagerRegistry::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $books = [new Book(), new Book()]; // Array of example books
        $response = $this->createMock(Response::class);

        $doctrine->expects($this->once())
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($bookRepository);

        $bookRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($books);

        $controller = $this->getMockBuilder(LibraryController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->with('library/read_many.html.twig', ['books' => $books])
            ->willReturn($response);
        
        $actualResponse = $controller->readMany($doctrine);

        $this->assertSame($response, $actualResponse);
    }

    public function testDelete(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->expects($this->once())
            ->method('getManager')
            ->willReturnSelf();
        $doctrine->expects($this->once())
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($bookRepository);

        $controller = new LibraryController();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Book not found');
        $controller->delete(1, $doctrine);
    }

    public function testUpdate(): void
    {
        $request = $this->createMock(Request::class);

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $doctrine = $this->createMock(ManagerRegistry::class);
        $doctrine->expects($this->once())
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($bookRepository);

        $controller = new LibraryController();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Book not found');
        $controller->update($request, 1, $doctrine);
    }
}