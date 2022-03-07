<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitiesController extends AbstractController
{
    #[Route('/cities', name: 'app_cities')]
    public function index(): Response
    {
        return $this->render('pages/cities.html.twig', [
            'controller_name' => 'CitiesController',
        ]);
    }
}
