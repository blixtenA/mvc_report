<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky/twig", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }

    #[Route("/", name: "/")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
/*
    #[Route("/api/quote/twig", name: "quote")]
    public function quote(): Response
    {
        $quotes = ["Don't eat yellow snow. - unknown",
        "Better to be rich and beautiful than poor and ugly. - okänd", 
        "If you don't like your job, you don't strike! You just go in every day, and do it really half assed. That's the American way. - Homer Simpson - coolfunnyquotes.com"];
        $today = date("Y/m/d");
        $timestamp = date("Y-m-d h:i:sa");

        $number = random_int(0, 2);
        $quote = $quotes[$number];

        $data = [
            'quote' => $quote,
            'today' => $today,
            'timestamp' => $timestamp 
        ];

        return $this->render('quote.html.twig', $data);
    }
*/
}