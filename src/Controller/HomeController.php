<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function listeSorties(Request $request, SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        $user->getRoles();

        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $etatLabels = ['Créée' => 'primary', 'Ouverte' => 'info', 'Clôturée' => 'warning', 'En cours' => 'success', 'Passée' => 'secondary', 'Annulée' => 'danger' ];

        $sorties = $sortieRepository->findAll();
        return $this->render('pages/home.html.twig', [
            "user" => $user,
            'sorties' => $sorties,
            'etatLabels' => $etatLabels,
            'sortieFilterForm' => $form->createView(),
        ]);
    }

    #[Route('/home/filtre', name: 'home_filtre')]
    public function filtreSorties(Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository): Response
    {


        $lesSorties = $sortieRepository->findBy([], []);
        return $this->render('pages/home.html.twig', [
            'sorties' => $lesSorties,

        ]);
    }

    #[Route('/home/creerSortie', name: 'sortie_creer')]
    public function creerSortie(EntityManagerInterface $entityManager): Response
    {
        return $this->render('pages/sortie.html.twig');
    }
}
