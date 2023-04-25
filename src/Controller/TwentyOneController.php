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

    /* Init the 21 game with a shuffled deck */
    #[Route("/twentyone/init", name: "twentyone_init_post", methods: ['GET', 'POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $logFile = "./my-errors.log";
        error_log("/twentyone/init \n", 3, $logFile);

        /* new Round or new Game? */
        $type = $request->request->get('type');

        /* receive player bet */
        $amount = $request->request->get('amount');

        error_log("amount: ", 3, $logFile);
        error_log("$amount \n", 3, $logFile);

        if ($type === "game") {
            $game = new Game($amount);
        } else {
            $game = $session->get("game");
            $game->startNewRound($amount);
        }
        
        /* Store in session */
        $session->set("game", $game);
        error_log("Check for game over \n", 3, $logFile);
        /* Check for game over */
        if ($game->checkGameOver()) {
            error_log("game over \n", 3, $logFile);

            return $this->redirectToRoute('twentyone_gameover');
        }
        error_log("NOT game over \n", 3, $logFile);

        return $this->redirectToRoute('twentyone_play');
    }

    #[Route("/twentyone/play", name: "twentyone_play", methods: ['GET', 'POST'])]
    public function twentyonePlay(
        SessionInterface $session
    ): Response {

        $logFile = "./my-errors.log";
        error_log("/twentyone/play \n", 3, $logFile);


        $game = $session->get("game");
        $data = $game->getData();
        $session->set("game", $game);

        return $this->render('twentyone/play.html.twig', $data);
    }

    #[Route("/twentyone/hit", name: "twentyone_hit", methods: ['GET', 'POST'])]
    public function hit(
        SessionInterface $session
    ): Response {

        $logFile = "./my-errors.log";
        error_log("/twentyone/hit \n", 3, $logFile);


        $game = $session->get("game");
        $continue = $game->hit();
        $session->set("game", $game); 
        error_log($continue." continue \n", 3, $logFile);

        /* Check for game over */
        if ($game->checkGameOver() or !$continue) {
            error_log("game over \n", 3, $logFile);            
            return $this->redirectToRoute('twentyone_gameover');
        }
        error_log("NOT game over \n", 3, $logFile);
        return $this->redirectToRoute('twentyone_play');
    }

    /* Play 21, player stands */
    #[Route("/twentyone/stand", name: "twentyone_stand", methods: ['GET', 'POST'])]
    public function standPlayer(
        SessionInterface $session
    ): Response {
        $logFile = "./my-errors.log";
        error_log("/twentyone/stand \n", 3, $logFile);

        $game = $session->get("game");        
        $game->stand();
        $session->set("game", $game);        

        return $this->redirectToRoute('twentyone_hit');
    }

    #[Route("/twentyone/gameOver", name: "twentyone_gameover", methods: ['GET'])]
    public function gameOver(
        SessionInterface $session
    ): Response {

        $logFile = "./my-errors.log";
        error_log("/twentyone/gameover \n", 3, $logFile);

        $game = $session->get("game");  
        $data = $game->endOfRound();

        $logFile = "./my-errors.log";
        error_log(json_encode($data), 3, $logFile);

        $session->set("game", $game);

        return $this->render('twentyone/play.html.twig', $data);
    }
}
