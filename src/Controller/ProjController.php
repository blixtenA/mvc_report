<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Proj\Game;
use App\Proj\Room;
use App\Proj\GameObject;
use App\Proj\Player;
use App\Proj\Event;
use App\Proj\Action;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProjController extends AbstractController
{
    #[Route('/proj', name: 'app_project')]
    public function index(): Response
    {
        return $this->render('proj/index.html.twig', [
            'controller_name' => 'ProjController',
        ]);
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/about/database", name: "proj_about_database")]
    public function aboutDatabase(): Response
    {
        return $this->render('proj/about_database.html.twig');
    }

    #[Route("/proj/player", name: "proj_game_player", methods: ['GET', 'POST'])]
    public function newPlayer(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($request->isMethod('POST')) {
            $playerName = $request->request->get('playerName');
            $avatar = $request->request->get('avatar');
    
            $avatarImagePath = match ($avatar) {
                'avatar1' => 'img/proj/characters/character1.png',
                'avatar2' => 'img/proj/characters/character2.png',
                default => 'img/proj/characters/default.png',
            };
    
            $player = new Player($playerName, $avatarImagePath);
            $session->set("player", $player);
            return $this->redirectToRoute('proj_game_start');
        }
    
        return $this->render('proj/new_player.html.twig');
    }

    #[Route("/proj/game", name: "proj_game_start", methods: ['GET'])]
    public function gamestart(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response
    {
        /* Load new Game and set the player */
        $gameID = 1;
        $game = new Game($gameID);
        $game->initGame($doctrine);
        $player = $session->get("player");
        $game->setPlayer($player);

        /* Save to session */
        $session->set("game", $game);
    
        /* Redirect to play */
        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/handle-event", name: "handle-event", methods: ['GET', 'POST'])]
    public function handleEvent(
        Request $request, 
        SessionInterface $session, 
        ManagerRegistry $doctrine
        ): Response
    {
        $entityManager = $doctrine->getManager();

        /* Get the game from the session and other IDs */
        $game = $session->get("game");
        $roomID = $game->getCurrentRoom()->getId();
        $gameObjectId = (int) $request->query->get('gameObjectId');
        $eventId = (int) $request->query->get('eventId');
        $location = $roomID;

        /* Retrieve the current game object from the room or player's inventory */
        $gameObject = $game->getCurrentRoom()->getGameObjectById($gameObjectId);
        if (!$gameObject) {
            $gameObject = $game->getPlayer()->getInventoryById($gameObjectId);
            $location = 0;
        }
    
        if (!$gameObject) {
            error_log("Object not found in controller", 0);
        }

        $event = new Event();
        $event->initEvent($gameObject, $eventId, $location, $doctrine);
    
        /* Do action */
        /** @phpstan-ignore-next-line */
        $action = new Action($game, $event, $gameObject, $entityManager);
        $reloadRoom = $action->perform();
        $messages = $action->getMessages();

        if ($reloadRoom) {
            $game->getCurrentRoom()->loadObjects(2, $doctrine);
        }
    
        /* Save the updated game to the session */
        $session->set("game", $game);
        $session->set("messages", $messages);
    
        /* Redirect to play */
        return $this->redirectToRoute('proj_game');

    }
    
    #[Route("/proj/play", name: "proj_game", methods: ['GET'])]
    public function gameplay(
        SessionInterface $session
    ): Response {
        /* Get the game from the session */
        $game = $session->get("game");
        $messages = $session->get("messages");
    
        /* Clear the messages from session */
        $session->remove("messages");
    
        return $this->render('proj/play.html.twig', [
            'game' => $game,
            'messages' => $messages,
        ]);
    }
    
}
