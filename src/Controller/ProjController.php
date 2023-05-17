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
    public function index(Request $request): Response
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

    #[Route("/proj/game", name: "proj_game_start", methods: ['GET'])]
    public function gamestart(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response
    {
        $entityManager = $doctrine->getManager();
        error_log("test",0);

        $game = new Game();

        $room = new Room(
            "Starting chamber", 
            "img/proj/backgrounds/background1.png", 
            "You find yourself in a dark room with two exits. You see a sawblade spinning on the wall to your left.",
            "3,4"
        );

        /* Load Events */
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
        $events = $eventRepository->findAll();
    
        $eventObjects = [];
        foreach ($events as $event) {
            
            $eventObject = new Event(
                $event->getId(),
                $event->getName(),
                $event->getRoute(),
                $event->getEventImages(),
                $event->getPositionX(),
                $event->getPositionY(),
                $event->getAnimationDelay(),
                $event->getText(),
                $event->getActions() !== null ? explode(',', implode(',', $event->getActions())) : []
            );
    
            /* Store the event object in the array with the event ID as the key */
            $eventObjects[$event->getId()] = $eventObject;
        }

        /* Load objects */
        $gameObjectRepository = $entityManager->getRepository(\App\Entity\GameObject::class);
        $gameObjects = $gameObjectRepository->findAll();
        $gameObjectsById = [];
        
        foreach ($gameObjects as $gameObject) {
            
            $newGameObject = new GameObject(
                $gameObject->getId(),
                $gameObject->getImage(),
                $gameObject->getPositionX(),
                $gameObject->getPositionY(),
                $gameObject->getName(),
                $gameObject->isClickable(),
                $gameObject->getOptionsArray()
            );

            foreach ($gameObject->getOptions() as $optionKey => $optionValue) {
                if (isset($eventObjects[$optionValue])) {
                    $newGameObject->addEvent($eventObjects[$optionValue]);
                }
            }
        
            $gameObjectsById[$gameObject->getId()] = $newGameObject;
        }

        /* TO DO: Load rooms */

        /* TO DO: Start game logic, map? */
    
        $room->addGameObject($gameObjectsById[1]);
        $room->addGameObject($gameObjectsById[2]);

        $player = new Player(
            "Anna",
            "img/proj/characters/character1.png"
        );

        $game->addRoom($room);
        $game->setCurrentRoom($room);
        $game->setPlayer($player);

        $session->set("game", $game);
    
        /* Redirect to play */
        return $this->redirectToRoute('proj_game');

    }

    #[Route("/proj/handle-event", name: "handle-event", methods: ['GET', 'POST'])]
    public function handleEvent(Request $request, SessionInterface $session, RouterInterface $router): Response
    {
        /* Get the game from the session */
        $game = $session->get("game");
    
        $gameObjectId = $request->query->get('gameObjectId');
        $eventId = $request->query->get('eventId');
    
        /* Retrieve the current game object from the room or player's inventory */
        $gameObject = $game->getCurrentRoom()->getGameObjectById($gameObjectId);
        if (!$gameObject) {
            $gameObject = $game->getPlayer()->getInventoryById($gameObjectId);
        }
    
        if (!$gameObject) {
            error_log("Object not found", 0);
        }
        error_log("Object found", 0);        
    
        /* Retrieve the event from the game object */
        $event = $gameObject->getEventById($eventId);
        if (!$event) {
            error_log("Event not found", 0);
        }
    
        $actions = $event->getActions();

        foreach ($actions as $actionType) {
            if ($game->getGameState() === 'gameover') {
                return $this->redirectToRoute('game_over');
            }
        
            $action = new Action($game, $event, $gameObject, $actionType);
            $messages = $action->perform();
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
