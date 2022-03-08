<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function listeSorties(SortieRepository $sortieRepository): Response
    {
        $lesSorties = $sortieRepository->findAll();
        return $this->render('pages/home.html.twig', [
            'sorties' => $lesSorties
        ]);
    }

    #[Route('/home/filtre', name: 'home_filtre')]
    public function filtreSorties(SortieRepository $sortieRepository): Response
    {
        #plus tard
        $lesSorties = $sortieRepository->findBy([], []);
        return $this->render('pages/home.html.twig', [
            'sorties' => $lesSorties
        ]);
    }

    #[Route('/home/creerSortie', name: 'sortie_creer')]
    public function creerSortie(EntityManagerInterface $entityManager): Response
    {
        return $this->render('pages/sortie.html.twig');
    }
}
