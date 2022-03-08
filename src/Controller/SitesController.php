<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitesController extends AbstractController
{
    #[Route('/sites', name: 'sites')]
    public function listeSites(): Response
    {
        /*
        $sites = $siteRepository->findAll();
        */
        return $this->render('pages/sites.html.twig', [
            /*'sites' => $sites,*/
        ]);
    }

    #[Route('/sites', name: 'sites_filtre')]
    public function filtresites(string $nom): Response
    {
        /*
        $sites = $siteRepository->findBy([], ['nom' => $nom ]);
        */
        return $this->render('pages/sites.html.twig', [
            /*'sites' => $sites,*/
        ]);
    }
}
