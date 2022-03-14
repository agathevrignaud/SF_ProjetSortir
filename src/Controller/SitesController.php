<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Entity\Site;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitesController extends AbstractController
{
    #[Route('/admin/sites', name: 'sites')]
    public function listeSites(SiteRepository $siteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sites = $siteRepository->findAll();
        return $this->render('pages/sites.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/admin/sites', name: 'sites_filtre')]
    public function filtresites(string $nom, SiteRepository $siteRepository): Response
    {
        $sites = $siteRepository->findBy([], ['nom' => $nom ]);
        return $this->render('pages/sites.html.twig', [
            'sites' => $sites,
        ]);
    }
    #[Route('/admin/site/ajouter', name: 'site_ajouter')]
    public function ajouterVille(Request $request,EntityManagerInterface $entityManager)
    {
        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();
            return $this->redirectToRoute('sites');
        }
        return $this->render('formFiles/siteForm.html.twig', [
            'siteForm' => $form->createView(),
        ]);
    }
}
