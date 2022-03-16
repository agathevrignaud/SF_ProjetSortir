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
         * Form Filtre sur le nom des sites
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
            }
        } else {
            $sites = $siteRepository->findAll();
        }

        /*
         * Form ajout/modif des sites
         */
        $site = new Site();
        $form = $this->createForm(SiteFormType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->get('id')->getData();
            $flashMessage = 'Le site '.$site->getNom().' a été ajouté !';

            if ($id) {
                $site = $siteRepository->find($id);
                $nom = $form->get('nom')->getData();
                $site->setNom($nom);

                $flashMessage = 'Le site '.$site->getNom().' a été modifié !';
            }

            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                $flashMessage
            );

            return $this->redirectToRoute('sites');
        }

        return $this->render('pages/administration/sites.html.twig', [
            'sites' => $sites,
            'siteForm' => $form->createView(),
            'siteFiltreForm' => $formFiltre->createView()
        ]);
    }

    #[Route('/admin/sites/{id}', name: 'sites_suppr')]
    public function supprimerSite(int $id,EntityManagerInterface $entityManager, Request $request, SiteRepository $siteRepository): Response
    {
        $site = $siteRepository->find($id);
        $entityManager->remove($site);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Le site '.$site->getNom().' a été supprimé !'
        );

        $sites = $siteRepository->findAll();
        $form = $this->createForm(SiteFormType::class, $site);
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

        return $this->render('pages/administration/sites.html.twig', [
            'sites' => $sites,
            'siteForm' => $form->createView(),
            'siteFiltreForm' => $formFiltre->createView()
        ]);
    }
}
