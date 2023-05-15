<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MetricsController extends AbstractController
{
    #[Route('/metrics', name: 'metrics')]
    public function index(): Response
    {
        return $this->render('metrics.html.twig', [
            'controller_name' => 'MetricsController',
        ]);
    }

}
