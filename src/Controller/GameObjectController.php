<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GameObject;
use App\Entity\Action;
use App\Entity\Room;
use App\Entity\Event;
use App\Entity\Game;
use App\Form\RoomType;
use App\Form\ActionType;
use App\Form\GameType;
use App\Form\EventType;
use App\Form\EventByObjectType;
use App\Entity\EventByObject;
use App\Entity\ObjectByRoom;
use App\Form\GameObjectType;
use App\Form\ObjectByRoomType;
use App\Repository\GameRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class GameObjectController extends AbstractController
{
    #[Route('/proj/game/object', name: 'app_game_object')]
    public function index(): Response
    {
        return $this->render('game_object/index.html.twig', [
            'controller_name' => 'GameObjectController',
        ]);
    }

    #[Route('/game/viewall', name: 'game_view_all')]
    public function readMany(ManagerRegistry $doctrine): Response
    {
        $gameObjectRepository = $doctrine->getRepository(GameObject::class);
        $gameObject = $gameObjectRepository->findAll();
    
        $roomRepository = $doctrine->getRepository(Room::class);
        $rooms = $roomRepository->findAll();
    
        $gameRepository = $doctrine->getRepository(Game::class);
        $gameIds = $gameRepository->findUniqueGameIds();
    
        $objectByRoomRepository = $doctrine->getRepository(ObjectByRoom::class);
        $objectByRooms = $objectByRoomRepository->findAll();
    
        $actionRepository = $doctrine->getRepository(Action::class);
        $actions = $actionRepository->findAll();
    
        $eventRepository = $doctrine->getRepository(Event::class);
        $events = $eventRepository->findAll();
    
        $eventByObjectRepository = $doctrine->getRepository(EventByObject::class);
        $eventByObjects = $eventByObjectRepository->findAll();
    
        return $this->render('proj/view_all.html.twig', [
            'gameObject' => $gameObject,
            'rooms' => $rooms,
            'gameIds' => $gameIds,
            'objectByRooms' => $objectByRooms,
            'actions' => $actions,
            'events' => $events,
            'eventByObjects' => $eventByObjects,
        ]);
    }

    #[Route('/game/object/{id}/edit', name: 'game_object_edit', requirements: ['id' => '\d+'])]
    public function editGameObject(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $gameObjectRepository = $doctrine->getRepository(GameObject::class);
        $gameObject = $gameObjectRepository->find($id);

        if (!$gameObject) {
            throw new NotFoundHttpException('GameObject not found');
        }

        $form = $this->createForm(GameObjectType::class, $gameObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('game_view_all');
        }

        return $this->render('proj/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('/game/room/{id}/edit', name: 'game_room_edit', requirements: ['id' => '\d+'])]
    public function editRoomObject(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $roomRepository = $doctrine->getRepository(Room::class);
        $room = $roomRepository->find($id);
    
        if (!$room) {
            throw new NotFoundHttpException('Room not found');
        }
    
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('game_view_all');
        }
    
        return $this->render('proj/edit_room.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
    
    #[Route('/game/{id}/edit', name: 'game_edit', requirements: ['id' => '\d+'])]
    public function editGame(Request $request, int $id, ManagerRegistry $doctrine, GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy(['game_id' => $id]);
    
        if (empty($games)) {
            throw new NotFoundHttpException('Games not found');
        }
    
        $form = $this->createFormBuilder(['games' => $games])
            ->add('games', CollectionType::class, [
                'entry_type' => GameType::class,
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedGames = $form->get('games')->getData();
    
            $entityManager = $doctrine->getManager();
            foreach ($updatedGames as $game) {
                $entityManager->persist($game);
            }
            $entityManager->flush();
    
            return $this->redirectToRoute('game_edit', ['id' => $id]);
        }
    
        return $this->render('proj/game_map_edit.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'gameRepository' => $gameRepository,
        ]);
    }
    
    #[Route('/game/object_by_room/{id}/edit', name: 'game_object_by_room_edit', requirements: ['id' => '\d+'])]
    public function editObjectByRoom(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $objectByRoomRepository = $doctrine->getRepository(ObjectByRoom::class);
        $objectByRoom = $objectByRoomRepository->find($id);

        $roomRepository = $doctrine->getRepository(Room::class);
        $room = $roomRepository->find($objectByRoom->getRoomId());

        $gameObjectRepository = $doctrine->getRepository(GameObject::class);
        $gameObject = $gameObjectRepository->find($objectByRoom->getObjectId());    
        $gameObjectImage = $gameObject ? $gameObject->getImage() : null;

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('objectByRoom', ObjectByRoomType::class, [
            'data' => $objectByRoom,
            'label' => false,
        ]);
        
        $form = $formBuilder->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($objectByRoom);
            $entityManager->flush();
        
            return $this->redirectToRoute('game_object_by_room_edit', ['id' => $id]);
        }
    
        return $this->render('proj/edit_object_by_room.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'roomBackground' => $room ? $room->getBackground() : null,
            'gameObjectImage' => $gameObjectImage,
        ]);
    }
    
    #[Route('/game/action/{id}/edit', name: 'game_action_edit', requirements: ['id' => '\d+'])]
    public function editAction(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $actionRepository = $doctrine->getRepository(Action::class);
        $action = $actionRepository->find($id);
    
        $form = $this->createForm(ActionType::class, $action);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('game_view_all');
        }
    
        return $this->render('proj/edit_action.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'action' => $action,
        ]);
    }

    #[Route('/game/event/{id}/edit', name: 'game_event_edit', requirements: ['id' => '\d+'])]
    public function editEvent(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $eventRepository = $doctrine->getRepository(Event::class);
        $event = $eventRepository->find($id);
    
        $form = $this->createForm(EventType::class, $event);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
    
            return $this->redirectToRoute('game_view_all');
        }
    
        return $this->render('proj/edit_event.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
    
    
    #[Route('/game/event_by_object/{id}/edit', name: 'game_event_by_object_edit', requirements: ['id' => '\d+'])]
    public function editEventByObject(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $eventByObjectRepository = $doctrine->getRepository(EventByObject::class);
        $eventByObject = $eventByObjectRepository->find($id);
    
        $actionRepository = $doctrine->getRepository(Action::class);
        $actions = $actionRepository->findAll();
    
        $gameObjectRepository = $doctrine->getRepository(GameObject::class);
        $gameObject = $gameObjectRepository->find($eventByObject->getObjectId());
        $objectName = $gameObject ? $gameObject->getName() : null;
    
        $eventRepository = $doctrine->getRepository(Event::class);
        $event = $eventRepository->find($eventByObject->getEventId());
        $eventName = $event ? $event->getName() : null;
    
        $roomRepository = $doctrine->getRepository(Room::class);
        $room = $roomRepository->find($eventByObject->getLocation());
        $roomName = $room ? $room->getName() : null;
    
        $form = $this->createForm(EventByObjectType::class, $eventByObject);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
    
            return $this->redirectToRoute('game_event_by_object_edit', ['id' => $id]);
        }
    
        return $this->render('proj/edit_event_by_object.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'actions' => $actions,
            'gameObject' => $gameObject,
            'objectName' => $objectName,
            'eventName' => $eventName,
            'roomName' => $roomName,
        ]);
    }
    
    #[Route('/game/object/create', name: 'game_object_create')]
    public function createObject(Request $request, ManagerRegistry $doctrine): Response
    {    
        $form = $this->createForm(GameObjectType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
    
                $gameObject = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($gameObject);
                $entityManager->flush();
    
                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }
    
        return $this->render('proj/create_object.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/game/object/delete/{id}', name: 'game_object_delete')]
    public function deleteObject(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $gameObject = $entityManager->getRepository(GameObject::class)->find($id);

        if (!$gameObject) {
            throw $this->createNotFoundException('Object not found.');
        }

        $entityManager->remove($gameObject);
        $entityManager->flush();

        return $this->redirectToRoute('game_view_all');
    }
    
    #[Route('/game/room/create', name: 'game_room_create')]
    public function createRoom(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(RoomType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $room = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($room);
                $entityManager->flush();

                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }

        return $this->render('proj/create_room.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/game/room/delete/{id}', name: 'game_room_delete')]
    public function deleteRoom(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $room = $entityManager->getRepository(Room::class)->find($id);
    
        if (!$room) {
            throw $this->createNotFoundException('Room not found.');
        }
    
        $entityManager->remove($room);
        $entityManager->flush();
    
        return $this->redirectToRoute('game_view_all');
    }
    
    #[Route('/game/room/object/create', name: 'object_by_room_create')]
    public function createObjectByRoom(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ObjectByRoomType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $objectByRoom = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($objectByRoom);
                $entityManager->flush();
    
                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }
    
        return $this->render('proj/create_object_by_room.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/game/room/object/delete/{id}', name: 'delete_object_by_room')]
    public function deleteObjectByRoom(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $objectByRoom = $entityManager->getRepository(ObjectByRoom::class)->find($id);

        if (!$objectByRoom) {
            throw $this->createNotFoundException('Object By Room not found.');
        }

        $entityManager->remove($objectByRoom);
        $entityManager->flush();

        return $this->redirectToRoute('game_view_all');
    }

    #[Route('/game/action/create', name: 'action_create')]
    public function createAction(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ActionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $action = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($action);
                $entityManager->flush();

                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }

        return $this->render('proj/create_action.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/game/action/delete/{id}', name: 'action_delete')]
    public function deleteAction(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $action = $entityManager->getRepository(Action::class)->find($id);

        if (!$action) {
            throw $this->createNotFoundException('Action not found.');
        }

        $entityManager->remove($action);
        $entityManager->flush();

        return $this->redirectToRoute('game_view_all');
    }

    #[Route('/game/event/create', name: 'event_create')]
    public function createEvent(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($event);
                $entityManager->flush();
    
                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }
    
        return $this->render('proj/create_event.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/game/event/delete/{id}', name: 'event_delete')]
    public function deleteEvent(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Event not found.');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('game_view_all');
    }

    #[Route('/game/event/object/create', name: 'event_by_object_create')]
    public function createEventByObject(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EventByObjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $eventByObject = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($eventByObject);
                $entityManager->flush();

                return $this->redirectToRoute('game_view_all');
            } else {
                error_log("Form is not valid", 0);
            }
        } else {
            error_log("Form is not submitted", 0);
        }

        return $this->render('proj/create_event_by_object.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/game/event/object/delete/{id}', name: 'event_by_object_delete')]
    public function deleteEventByObject(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $eventByObject = $entityManager->getRepository(EventByObject::class)->find($id);

        if (!$eventByObject) {
            throw $this->createNotFoundException('Event By Object not found.');
        }

        $entityManager->remove($eventByObject);
        $entityManager->flush();

        return $this->redirectToRoute('game_view_all');
    }


}
