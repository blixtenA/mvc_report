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
use App\Card\Player;
use App\Card\Rules;
use App\Card\Game;

class TwentyOneController extends AbstractController
{
    /* Home for 21 game */
    #[Route("/twentyone", name: "twentyone_start", methods: ['GET'])]
    public function card(): Response
    {
        return $this->render('twentyone/home.html.twig');
    }

    /* Docs for 21 */
    #[Route("/twentyone/doc", name: "twentyone_doc")]
    public function doc(): Response
    {
        return $this->render('twentyone/doc.html.twig');
    }

    /**
    * @SuppressWarnings(PHPMD.ElseExpression)
    */
    /* Init the 21 game with a shuffled deck */
    #[Route("/twentyone/init", name: "twentyone_init_post", methods: ['GET', 'POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {

        /* new Round or new Game? */
        $type = $request->request->get('type');

        /* receive player bet */
        $amount = $request->request->get('amount');
        if ($type === "game") {
            $game = new Game((int) $amount);
        } else {
            $game = $session->get("game");
            $game->startNewRound($amount);
        }

        /* Store in session */
        $session->set("game", $game);
        /* Check for game over */
        if ($game->checkGameOver()) {
            return $this->redirectToRoute('twentyone_gameover');
        }
        return $this->redirectToRoute('twentyone_play');
    }

    /* Renders the main play page for the game. */
    #[Route("/twentyone/play", name: "twentyone_play", methods: ['GET', 'POST'])]
    public function twentyonePlay(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $data = $game->getData();
        $session->set("game", $game);

        return $this->render('twentyone/play.html.twig', $data);
    }

    /* Hit logic for drawing cards */
    #[Route("/twentyone/hit", name: "twentyone_hit", methods: ['GET', 'POST'])]
    public function hit(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $continue = $game->hit();
        $session->set("game", $game);

        /* Check for game over */
        if ($game->checkGameOver() or !$continue) {
            return $this->redirectToRoute('twentyone_gameover');
        }

        /* Check if it's the bank's turn */
        if (!$game->isPlayerTurn()) {
            return $this->redirectToRoute('twentyone_hit');
        }

        /* Player turn and game continues */
        return $this->redirectToRoute('twentyone_play');
    }

    /* Play 21, player stands */
    #[Route("/twentyone/stand", name: "twentyone_stand", methods: ['GET', 'POST'])]
    public function standPlayer(
        SessionInterface $session
    ): Response {
        $game = $session->get("game");
        $game->stand();
        $session->set("game", $game);

        return $this->redirectToRoute('twentyone_hit');
    }

    #[Route("/twentyone/gameOver", name: "twentyone_gameover", methods: ['GET'])]
    public function gameOver(
        SessionInterface $session
    ): Response {

        $game = $session->get("game");
        $data = $game->endOfRound();

        $session->set("game", $game);

        return $this->render('twentyone/play.html.twig', $data);
    }
}
