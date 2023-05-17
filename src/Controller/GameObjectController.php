<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GameObject;
use App\Form\GameObjectType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GameObjectController extends AbstractController
{
    #[Route('/game/object', name: 'app_game_object')]
    public function index(): Response
    {
        return $this->render('game_object/index.html.twig', [
            'controller_name' => 'GameObjectController',
        ]);
    }

    #[Route('/game/createObject', name: 'game_object_create')]
    public function createGameObject(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $gameobject = new GameObject();

        /* broken */

    }

    #[Route('/game/showobjects', name: 'game_object_show_all')]
    public function showAllObjects(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $gameObjects = $entityManager->getRepository(GameObject::class)->findAll();
        $serializedData = $serializer->normalize($gameObjects, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['events']]);
        $jsonContent = json_encode($serializedData, JSON_PRETTY_PRINT);
    
        return new JsonResponse($jsonContent, 200, [], true);
    }
}
