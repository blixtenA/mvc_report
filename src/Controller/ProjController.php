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

        /* Load Game */
        $gameID = 1;

        $gameRepository = $entityManager->getRepository(\App\Entity\Game::class);
        $map = $gameRepository->findBy(['game_id' => $gameID]); 

        // Extract room IDs and positions from the retrieved game entity
        foreach ($map as $gameEntity) {
            $roomIDs[] = $gameEntity->getRoomId();
            $roomPositions[$gameEntity->getRoomId()] = [
                'pos_x' => $gameEntity->getPosX(),
                'pos_y' => $gameEntity->getPosY(),
            ];
        }

        /* Fetch the rooms from the Room entity using the extracted room IDs */
        $roomRepository = $entityManager->getRepository(\App\Entity\Room::class);
        $rooms = $roomRepository->findBy(['id' => $roomIDs]);

        /* Prepare Rooms and load them into the game */
        $roomObjects = []; // Array to store the created room objects

        foreach ($rooms as $roomEntity) {
            $roomID = $roomEntity->getId();
            $start = null;
        
            foreach ($map as $gameEntity) {
                if ($gameEntity->getRoomId() === $roomID) {
                    $start = $gameEntity->isStart();
                    break;
                }
            }

            $room = new Room(
                $roomEntity->getId(),
                $roomEntity->getName(),
                $roomEntity->getBackground(),
                $roomEntity->getDescription(),
                null,
                $start
            );
        
            $roomObjects[$roomEntity->getId()] = $room; // Store the room object using the room ID
        
            error_log("added room: ".$room->getName(),0);
        }
        
        /* Find neighboring rooms based on coordinates */
        foreach ($rooms as $roomEntity) {
            $roomPositionX = $roomPositions[$roomEntity->getId()]['pos_x'];
            $roomPositionY = $roomPositions[$roomEntity->getId()]['pos_y'];
        
            foreach ($rooms as $neighborEntity) {

                $neighborPositionX = $roomPositions[$neighborEntity->getId()]['pos_x'];
                $neighborPositionY = $roomPositions[$neighborEntity->getId()]['pos_y'];
        
                if ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY - 1) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('South', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY + 1) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('North', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX - 1 && $neighborPositionY === $roomPositionY) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('West', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX + 1 && $neighborPositionY === $roomPositionY) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('East', $roomObjects[$neighborEntity->getId()]);
                }
            }
        
            $game->addRoom($roomObjects[$roomEntity->getId()]);
        }

        /* Load Objects into each Room */
        $objectByRoomRepository = $entityManager->getRepository(\App\Entity\ObjectByRoom::class);
        $gameObjectsRepository = $entityManager->getRepository(\App\Entity\GameObject::class);

        foreach ($game->getRooms() as $room) {
            $roomID = $room->getId();
//            error_log("roomid: " . $roomID, 0);
            $objectByRooms = $objectByRoomRepository->findBy(['room_id' => $roomID]);
//            error_log("objectByRooms: " . print_r($objectByRooms, true));

            $objectIDs = [];

            foreach ($objectByRooms as $objectByRoom) {
                if ($objectByRoom->getSequence() === 1) {
                    $objectIDs[] = $objectByRoom->getObjectId();
                }
            }

//            error_log("objectIDs: " . print_r($objectIDs, true));
//            error_log("length of objectIds: " . count($objectIDs), 0);

            $gameObjects = $gameObjectsRepository->findBy(['id' => $objectIDs]);

            foreach ($gameObjects as $gameObject) {
                error_log("roomID: ". $roomID,0);
                error_log("gameobjectID: ". $gameObject->getId(),0);
                /* Find the object_by_room entry for the current GameObject */
                $objectByRoom = $objectByRoomRepository->findOneBy([
                    'room_id' => $roomID,
                    'object_id' => $gameObject->getId(),
                ]);

                error_log("object id: ". $gameObject->getId(),0);
                error_log("object effect: ". $gameObject->getEffect(),0);

                /* Retrieve the position values from object_by_room */
                $positionX = $objectByRoom->getPositionX();
                $positionY = $objectByRoom->getPositionY();
                $positionZ = $objectByRoom->getPositionZ();

                $newGameObject = new GameObject(
                    $gameObject->getId(),
                    $gameObject->getImage(),
                    $gameObject->getName(),
                    $positionX,
                    $positionY,
                    $positionZ,
                    $gameObject->isClickable(),
                    null,
                    $gameObject->getEffect(),
                );

                error_log("object: " . $newGameObject->getName(), 0);

                /* Fetch the event IDs associated with the current GameObject */
                $eventByObjectRepository = $entityManager->getRepository(\App\Entity\EventByObject::class);
                $eventIDs = $eventByObjectRepository->findEventIDsByObjectIDAndLocation($gameObject->getId(), $roomID);
                error_log("eventIDs: " . print_r($eventIDs, true), 0);
                /* Retrieve the corresponding events based on the fetched event IDs */
                $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
                $events = $eventRepository->findBy(['id' => $eventIDs]);

                /* Create an array of event_id/name pairs for the current GameObject */
                $eventOptions = [];
                foreach ($events as $event) {
                    $eventOptions[$event->getId()] = $event->getName();
                    error_log("event: ". $event->getName(),0);
                    error_log("eventId: ". $event->getId(),0);

                }

                /* Set the event options for the current GameObject */
                foreach ($eventOptions as $eventID => $eventName) {
                    error_log("adding: ". $eventID . " name: " . $eventName,0);
                    $newGameObject->addOption($eventID, $eventName);
                    error_log("eventOptions: " . print_r($eventOptions, true), 0);
                }

                $room->addGameObject($newGameObject);


            }
        }

        /* to do: custom player */
        $player = new Player(
            "Anna",
            "img/proj/characters/character1.png"
        );
        $game->setPlayer($player);

        /* Set the starting room and let the mayhem begin. */
        $startingRoom = null;
        foreach ($game->getRooms() as $room) {
            if ($room->isStart()) {
                $startingRoom = $room;
                break;
            }
        }
        $game->setCurrentRoom($startingRoom);

        error_log($game->getCurrentRoom()->getName(),0);

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
        $eventByObject = $eventByObjectRepository->findOneBy(['event_id' => $eventId, 'location' => $roomID]);        
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);

        if ($eventByObject) {
            $location = $eventByObject->getLocation();
            $actions = [
                $eventByObject->getAction1(),
                $eventByObject->getAction2(),
                $eventByObject->getAction3(),
                $eventByObject->getAction4(),
                $eventByObject->getAction5(),
            ];

            // Find the corresponding event record based on eventId
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
