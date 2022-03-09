<?php

namespace App\Controller;

use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VillesController extends AbstractController
{
    #[Route('/villes', name: 'villes')]
    public function listeVilles(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();
        return $this->render('pages/villes.html.twig', [
            'villes' => $villes,
        ]);
    }

    #[Route('/villes', name: 'villes_filtre')]
    public function filtreVilles(string $nom, VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findBy([], ['nom' => $nom]);
        return $this->render('pages/villes.html.twig', [
            'villes' => $villes,
        ]);
    }
}
