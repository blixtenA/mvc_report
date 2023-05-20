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

        /* Load Game */
        $gameID = 1;
        $game = new Game($gameID);
        $game->initGame($doctrine);

        /* to do: custom player */
        $player = new Player(
            "Anna",
            "img/proj/characters/character1.png"
        );
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
        RouterInterface $router,
        ManagerRegistry $doctrine
        ): Response
    {
        error_log("entering handle event",0);
        $entityManager = $doctrine->getManager();

        /* Get the game from the session */
        $game = $session->get("game");
        $roomID = $game->getCurrentRoom()->getId();
    
        $gameObjectId = $request->query->get('gameObjectId');
        $eventId = $request->query->get('eventId');

        error_log("eventId: ". $eventId,0);
        error_log("gameObjectId: ". $gameObjectId,0);
    
        /* Retrieve the current game object from the room or player's inventory */
        $gameObject = $game->getCurrentRoom()->getGameObjectById($gameObjectId);
        if (!$gameObject) {
            $gameObject = $game->getPlayer()->getInventoryById($gameObjectId);
        }
    
        if (!$gameObject) {
            error_log("Object not found in controller", 0);
        }
    
        /* Retrieve the event from the database */
        $eventByObjectRepository = $entityManager->getRepository(\App\Entity\EventByObject::class);
        $eventByObject = $eventByObjectRepository->findOneBy([
            'event_id' => $eventId,
            'object_id' => $gameObjectId,
            'location' => $roomID
        ]);
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
        $event = null;

        if ($eventByObject) {
            $location = $eventByObject->getLocation();
            error_log("location ". $location,0);
            error_log("ebo id ". $eventByObject->getId(),0);
            $actions = [
                $eventByObject->getAction1(),
                $eventByObject->getAction2(),
                $eventByObject->getAction3(),
                $eventByObject->getAction4(),
                $eventByObject->getAction5(),
            ];

            /* Find the corresponding event record based on eventId */
            $event = $eventRepository->findOneBy(['id' => $eventByObject->getEventId()]);

            if ($event) {
                $eventId = $event->getId();
                $text = $event->getText();
                $name = $event->getName();

            /* Create a new Event object with the data */
            $event = new Event($eventId, $name, $text, $location, $actions);
        } else {
            error_log("event not found!",0);
        }
    }
    
    /* Do action */
        /** @phpstan-ignore-next-line */
        $action = new Action($game, $event, $gameObject, $entityManager);
        $action->perform();
        $messages = $action->getMessages();
    
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
