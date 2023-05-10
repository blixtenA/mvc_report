<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Entity\Book;
use App\Card\Game;
use Doctrine\Persistence\ManagerRegistry;

class ApiController extends AbstractController
{
    /* Landing page for API, display available controllers */
    #[Route("/api/", name: "api_land")]
    public function apiLand(
        SessionInterface $session
    ): Response {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }

        return $this->render('api_landing.html.twig');
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    /* Show all cards in session deck, sorted by color & value */
    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function apiDeck(
        SessionInterface $session
    ): Response {
        if ($session->has('deck')) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
        }
        $deck->sortRemainingCards();

        $data = [
            'cards' => $deck->getCardsAsString(),
        ];

        $session->set("deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    /* Show the current standing in the game */
    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function apiGame(
        SessionInterface $session
    ): Response {
        if ($session->has('game')) {
            $game = $session->get("game");
        } else {
            $game = new Game(10);
        }

        $data = [
            'game' => $game->getData(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }    //anbl tag

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    /* Shuffle tha deck like a pro */
    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["POST"])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        if ($session->has('deck')) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
        }
        $deck->mixCards();

        $data = [
            'cards' => $deck->getCardsAsString(),
        ];

        $session->set("deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    /* Draw 1 or more cards. */
    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ["POST"])]
    public function apiDeckDraw(
        Request $request,
        SessionInterface $session
    ): Response {

        $numCards = $request->request->get('num_cards');

        if ($session->has('deck')) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
        }

        $hand = new CardHand($deck, (int) $numCards);
        $data = [
            "cards" => $hand->getCardsAsString(),
            "remaining" => $deck->remainingCards()
        ];

        $session->set("deck", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /* The library books */
    #[Route("/api/library/books", name: "api_library_books")]
    public function apiLibraryDraw(
        ManagerRegistry $doctrine
    ): Response {
        $bookRepository = $doctrine->getRepository(Book::class);
        $books = $bookRepository->findAll();

        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'isbn' => $book->getISBN(),
                'author' => $book->getAuthor(),
                'image' => $book->getImage(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /* The library books by ISBN */
    #[Route("/api/library/book/{isbn}", name: "api_library_isbn", requirements: ['isbn' => '\d+'])]
    public function apiLibraryISBN(int $isbn, ManagerRegistry $doctrine): Response
    {
        $bookRepository = $doctrine->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'isbn' => $book->getISBN(),
            'author' => $book->getAuthor(),
            'image' => $book->getImage(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

}
