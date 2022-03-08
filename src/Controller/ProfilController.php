<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/{id}', name: 'profil_details')]
    public function detailsProfil(int $id): Response
    {
        # à finir
        $profil = "";
        return $this->render('pages/profil.html.twig', [
            'profil' => $profil
        ]);
    }

    #[Route('/profil/{id}', name: 'profil_modifier')]
    public function modifierProfil(EntityManagerInterface $entityManager): Response
    {
        return $this->render('pages/profil.html.twig', [
            'profil' => ''
        ]);
    }
}
