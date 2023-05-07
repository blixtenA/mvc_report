<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Exception;

class CardGameController extends AbstractController
{
    /* Init the card game */
    #[Route("/card/init", name: "card_init_post", methods: ['GET', 'POST'])]
    public function initCallback(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $deck->getCards();

        $session->set("deck", $deck);

        return $this->redirectToRoute('card');
    }

    /* Home for Card game */
    #[Route("/card", name: "card", methods: ['GET'])]
    public function card(): Response
    {
        return $this->render('card/home.html.twig');
    }

    /* Show all cards in a full deck */
    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "cards" => $deck->getCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    /* Shuffle the deck and put a fresh deck in the session */
    #[Route("/card/shuffle", name: "card_shuffle", methods: ['GET', 'POST'])]
    public function shuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $deck->mixCards();
        $session->set("deck", $deck);

        $data = [
            "cards" => $deck->getCards()
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    /* Draw 1 card and display count of remaining cards */
    #[Route("/card/draw", name: "card_draw", methods: ['GET', 'POST'])]
    public function play(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");
        $hand = new CardHand($deck, 1);
        $data = [
            "cards" => $hand->getCards(),
            "remaining" => $deck->remainingCards()
        ];

        $session->set("deck", $deck);
        return $this->render('card/draw.html.twig', $data);
    }

    /* Draw n cards and display count of remaining cards */
    #[Route("/card/draw/{num<\d+>}", name: "draw_card_number")]
    public function playSeveral(
        int $num,
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");

        if ($num > 52) {
            throw new Exception("Can not draw more than 52 cards!");
        } elseif ($num > $deck->remainingCards()) {
            throw new Exception("Not enough cards left in the deck!");
        }

        $hand = new CardHand($deck, $num);
        $data = [
            "cards" => $hand->getCards(),
            "remaining" => $deck->remainingCards()
        ];

        $session->set("deck", $deck);
        return $this->render('card/draw.html.twig', $data);
    }

    /* Get route for getting how many cards the user wants to draw. */
    #[Route("/card/drawmany", name: "draw_many_get", methods: ['GET'])]
    public function manyCards(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");
        $data = [
            "remaining" => $deck->remainingCards()
        ];
        return $this->render('card/drawmany.html.twig', $data);
    }

    /* Post route for redirecting to the draw many route. */
    #[Route("/card/drawmany", name: "draw_many_post", methods: ['POST'])]
    public function manyCardsPost(
        Request $request,
    ): Response {
        $numCards = $request->request->get('num_cards');

        return $this->redirectToRoute('draw_card_number', ['num' => $numCards]);
    }
}
