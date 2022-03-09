<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfilType;
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

        if(empty($profil)) {
            throw $this->createNotFoundException('utilisateur introuvable');
        }
        return $this->render('pages/profil.html.twig', [
            'profil' => $profil
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
    //#[ParamConverter("profil", class: "App\Entity\User")]
    public function modifierProfil(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher, User $user)
    {
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'profil modifié !');
            return $this->redirectToRoute('sites');
        }

        return $this->render('pages/editProfil.html.twig', [
            'editProfilForm' => $form->createView()
        ]);
    }

    public function modifierPassword(){

        return $this->render('pages/editProfil.html.twig', [
            'editPasswordForm' => $form->createView()
        ]);
    }
}
