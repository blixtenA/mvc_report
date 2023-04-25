<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api/quote", name: "quote")]
    public function jsonQuote(): Response
    {
        $quotes = ["Dont eat yellow snow. - unknown",
        "Better to be rich and beautiful than poor and ugly. - unknown",
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

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
