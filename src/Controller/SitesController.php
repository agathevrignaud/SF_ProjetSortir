<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Entity\Site;
use App\Form\SiteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitesController extends AbstractController
{

    #[Route('/admin/sites', name: 'sites')]
    public function afficherSites(EntityManagerInterface $entityManager, Request $request, SiteRepository $siteRepository): Response
    {
        /*
         * Form Filtre sur le nom des villes
         */
        $formFiltre = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'label' => 'Le nom contient :',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom du site...',
                )
            ))
            ->add('rechercher', SubmitType::class, array(
                'label' => 'Rechercher',
            ))
            ->getForm();

        $formFiltre->handleRequest($request);

        if ($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            $data = $formFiltre->getData();
            if (!empty(array_filter($data, function($i) { return $i; }))) {
                $sites = $siteRepository->findSiteByFilter($data);
            } else {
                $sites = $siteRepository->findAll();
            }
        } else {
            $sites = $siteRepository->findAll();
        }

        /*
         * Form ajout des villes
         */
        $site = new Site();
        $form = $this->createForm(SiteFormType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();
            return $this->redirectToRoute('sites');
        }

        return $this->render('pages/sites.html.twig', [
            'sites' => $sites,
            'siteForm' => $form->createView(),
            'siteFiltreForm' => $formFiltre->createView()
        ]);
    }

}
