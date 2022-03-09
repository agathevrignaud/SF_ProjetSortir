<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfilType;
use App\Form\EditPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserController extends AbstractController implements PasswordUpgraderInterface

{
    #[Route('/profil/{id}', name: 'profil_details')]
    public function detailsProfil(EntityManagerInterface $entityManager ,int $id)
    {
        $userRepository = $entityManager->getRepository(User::class);

        $profil = $userRepository->find($id);
        $siteName = $profil->getSite()->getNom();

        if(empty($profil)) {
            throw $this->createNotFoundException('utilisateur introuvable');
        }
        return $this->render('pages/profil.html.twig', [
            'profil' => $profil,
            'site' =>$siteName
        ]);

    }

    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        // set the new hashed password on the User object
        $user->setPassword($newHashedPassword);

        // execute the queries on the database
        $this->getEntityManager()->flush();
    }


    #[Route('/profil/modifier/{id}', name: 'profil_modifier')]
    public function modifierProfil(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher, User $user)
    {
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('photo')->getData();
            if ($image != null) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $user->setPhoto($fichier);
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $entityManager->persist($user);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Profil modifié !');
            return $this->redirectToRoute('profil_details', ['id' => $user->getId()]);
        }

        return $this->render('pages/editProfil.html.twig', [
            'editProfilForm' => $form->createView()
        ]);
    }

    #[Route('/profil/modifier/motdepasse/{id}', name: 'motdepasse_modifier')]
    public function modifierPassword(Request $request,EntityManagerInterface $entityManager, User $user){

        $form = $this->createForm(EditPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Mot de passe modifié !');
            return $this->redirectToRoute('profil_details',['id'=> $user->getId() ]);
        }

        return $this->render('pages/editPassword.html.twig', [
            'editPasswordForm' => $form->createView()
        ]);
    }
}
