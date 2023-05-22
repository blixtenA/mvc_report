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

/** @SuppressWarnings("CouplingBetweenObjects") */
class ProjJSONController extends AbstractController
{
    /* JSON 1 */
    #[Route('/proj/api/showobjects', name: 'game_object_show_all')]
    public function showAllObjects(ManagerRegistry $doctrine, NormalizerInterface $normalizer): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $gameObjects = $entityManager->getRepository(GameObject::class)->findAll();
        $normalizedData = $normalizer->normalize($gameObjects, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['events']]);
        $jsonContent = json_encode($normalizedData, JSON_PRETTY_PRINT);

        return new JsonResponse($jsonContent, 200, [], true);
    }
    
    /* JSON 2 */
    #[Route('/proj/api/showactions', name: 'game_action_show_all')]
    public function showAllActions(ManagerRegistry $doctrine, NormalizerInterface $normalizer): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $gameActions = $entityManager->getRepository(Action::class)->findAll();
        $normalizedData = $normalizer->normalize($gameActions, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['events']]);
        $jsonContent = json_encode($normalizedData, JSON_PRETTY_PRINT);
    
        return new JsonResponse($jsonContent, 200, [], true);
    }

    /* JSON 3 */
    #[Route('/proj/api/createObject', name: 'game_object_create_JSON', methods: ['POST'])]
    public function createObjectJson(
        ManagerRegistry $doctrine, 
        SerializerInterface $serializer
        ): JsonResponse
    {
        // Create a new GameObject instance with hard-coded dummy values
        $gameObject = new GameObject();
        $gameObject->setImage('dummy-image.jpg');
        $gameObject->setName('Dummy Name');
        $gameObject->setImage2(null);
        $gameObject->setEffect(null);
    
        $entityManager = $doctrine->getManager();
        $entityManager->persist($gameObject);
        $entityManager->flush();
    
        $createdObject = $entityManager->getRepository(GameObject::class)->find($gameObject->getId());

        if (!$createdObject) {
            throw new NotFoundHttpException('Failed to retrieve the created object');
        }

        /* Serialize the created object to JSON */
        $jsonContent = $serializer->serialize($createdObject, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['events']]);

        return new JsonResponse($jsonContent, JsonResponse::HTTP_CREATED, [], true);
    }

    /* JSON 4 */
    #[Route('/proj/api/viewall', name: 'game_view_all_JSON')]
    public function readManyJSON(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $gameObjectRepository = $doctrine->getRepository(GameObject::class);
        $gameObjects = $gameObjectRepository->findAll();

        $roomRepository = $doctrine->getRepository(Room::class);
        $rooms = $roomRepository->findAll();

        $gameRepository = $doctrine->getRepository(Game::class);
        $gameIds = $gameRepository->findUniqueGameIds(); // @phpstan-ignore-line

        $objByRoomRepository = $doctrine->getRepository(ObjectByRoom::class);
        $objectByRooms = $objByRoomRepository->findAll();

        $actionRepository = $doctrine->getRepository(Action::class);
        $actions = $actionRepository->findAll();

        $eventRepository = $doctrine->getRepository(Event::class);
        $events = $eventRepository->findAll();

        $eventByObjRepository = $doctrine->getRepository(EventByObject::class);
        $eventByObjects = $eventByObjRepository->findAll();

        $normalizedData = [
            'gameObject' => $gameObjects,
            'rooms' => $rooms,
            'gameIds' => $gameIds,
            'objectByRooms' => $objectByRooms,
            'actions' => $actions,
            'events' => $events,
            'eventByObjects' => $eventByObjects,
        ];

        $jsonContent = $serializer->serialize($normalizedData, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['events']]);
        
        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /* JSON 5 */
    #[Route('/proj/api/deleteObject/{id}', name: 'game_object_delete_JSON', methods: ['POST'])]
    public function deleteObjectJson(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $objectId = $request->request->get('id');
    
        if ($objectId === '__id__' || $objectId === null) {
            throw new NotFoundHttpException('Object ID is required');
        }
    
        $entityManager = $doctrine->getManager();
        $gameObject = $entityManager->getRepository(GameObject::class)->find($objectId);
    
        if (!$gameObject) {
            throw new NotFoundHttpException('GameObject not found');
        }
    
        $entityManager->remove($gameObject);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'GameObject deleted successfully']);
    }
}