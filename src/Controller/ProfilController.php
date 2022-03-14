<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProfilController extends AbstractController implements PasswordUpgraderInterface

{
    #[Route('/profil/{id}', name: 'profil')]
    public function afficherProfil(int $id, EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, UserRepository $userRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $profil = $userRepository->find($id);
        $profilImg = $profil->getPhoto();

        if(empty($profil)) {
            throw $this->createNotFoundException("L'utilisateur n'existe pas.");
        }

        /*
         * Form update du profil & du mdp
         */
        $form = $this->createForm(UserFormType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* Photo de profil */
            $photoProfil = $form['photo']->getData();
            if($photoProfil) {
                $originalFilename = pathinfo($photoProfil->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoProfil->guessExtension();

                try {
                    $photoProfil->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw $this->createNotFoundException("Erreur lors de l'upload de l'image.");
                }
                $profil->setPhoto($newFilename);
            } elseif ($profilImg) {
                $profil->setPhoto($profilImg);
            }

            /* Mot de passe */


            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('profil', ['id' => $profil->getId()]);
        }

        return $this->render('pages/profil.html.twig', [
            'profil' => $profil,
            'profilForm' => $form->createView()
        ]);

    }

    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->flush();
    }



}
