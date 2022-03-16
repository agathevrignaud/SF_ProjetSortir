<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'utilisateurs')]
    public function afficherUtilisateurs(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {
        /*
         * Form Filtre sur le prénom/nom des utilisateurs
         */
        $formFiltre = $this->createFormBuilder()
            ->add('nom', TextType::class, array(
                'label' => 'Le nom/prénom contient :',
                'required' => true,
                'attr' => array(
                    'placeholder' => "Pseudo, nom, prénom...",
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
                $utilisateurs = $userRepository->findUtilisateurByFilter($data);
            }
        } else {
            $utilisateurs = $userRepository->findAll();
        }

        return $this->render('pages/administration/utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
            'utilisateurFiltreForm' => $formFiltre->createView()
        ]);
    }

    #[Route('/admin/utilisateurs/{id}', name: 'utilisateur_actif_inactif')]
    public function activerUtilisateur(int $id,EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $user->setActif(!$user->getActif());

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            $user->getActif() ?
                "L'utilisateur ".$user->getPseudo()." a été activé !"
                :
                "L'utilisateur ".$user->getPseudo()." a été désactivé !"
        );

        return $this->redirectToRoute('utilisateurs', []);
    }

}
