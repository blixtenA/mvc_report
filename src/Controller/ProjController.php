<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Proj\Game;
use App\Proj\Room;
use App\Proj\GameObject;


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

    #[Route("/proj/game", name: "proj_game_start")]
    public function gamestart(): Response
    {
        $room = new Room("img/proj/backgrounds/background1.png");
    
        $gameObject = new GameObject(
            "img/proj/objects/sawblade2.png",
            10,
            10,
            "sawblade",
            true,
            ["Activate", "Pick up"]
        );

        $gameObject2 = new GameObject(
            "img/proj/objects/key1.png",
            100,
            100,
            "key",
            true,
            ["Activate", "Pick up"]
        );
        
    
        $room->addGameObject($gameObject);
        $room->addGameObject($gameObject2);
    
        return $this->render('proj/play.html.twig', [
            'room' => $room,
        ]);
    }

}