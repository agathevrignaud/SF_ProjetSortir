<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{

    #[Route('/sortie/{action}', name: 'app_trip')]
    public function display(string $action): Response
    {
        return $this->render('pages/trip.html.twig', [
            'action' => $action,
        ]);
    }


}
