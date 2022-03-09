<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sortie/{id}', name: 'sortie_modifier')]
    public function modifierSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = $sortieRepository->find($id);

        #récup les infos du form + persist + flush
        return $this->render('pages/sortie.html.twig', [
            'sortie' => $sortie,
            'estValide' => true
        ]);
    }

    #[Route('/sortie/{id}', name: 'sortie_details')]
    public function detailsSortie(int $id, SortieRepository $sortieRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = $sortieRepository->find($id);
        return $this->render('pages/sortie.html.twig', [
            'sortie' => $sortie
        ]);
    }

}
