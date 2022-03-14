<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use App\Form\VilleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VillesController extends AbstractController
{
    #[Route('/admin/villes', name: 'villes')]
    public function afficherVilles(EntityManagerInterface $entityManager, Request $request, VilleRepository $villeRepository): Response
    {

        /*
         * Form Filtre sur le nom des villes
         */
        $formFiltre = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'label' => 'Le nom contient :',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom de la ville...',
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
                $villes = $villeRepository->findVilleByFilter($data);
            }
        } else {
            $villes = $villeRepository->findAll();
        }

        /*
         * Form ajout des villes
         */
        $ville = new Ville();
        $form = $this->createForm(VilleFormType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'La ville '.$ville->getNom().' a été ajoutée !'
            );

            return $this->redirectToRoute('villes');
        }

        return $this->render('pages/villes.html.twig', [
            'villes' => $villes,
            'villeForm' => $form->createView(),
            'villeFiltreForm' => $formFiltre->createView()
        ]);
    }

}
