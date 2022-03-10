<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VillesController extends AbstractController
{
    #[Route('/admin/villes', name: 'villes')]
    public function listeVilles(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();
        return $this->render('pages/villes.html.twig', [
            'villes' => $villes,
        ]);
    }

    #[Route('/admin/villes', name: 'villes_filtre')]
    public function filtreVilles(string $nom, VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findBy([], ['nom' => $nom]);
        return $this->render('pages/villes.html.twig', [
            'villes' => $villes,
        ]);
    }
    #[Route('/admin/villes/ajouter', name: 'villes_ajouter')]
    public function ajouterVille(Request $request,EntityManagerInterface $entityManager)
    {
        $ville = new Ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
            return $this->redirectToRoute('villes');
        }
        return $this->render('formFiles/villesForm.html.twig', [
            'villeForm' => $form->createView(),
        ]);
    }
}
