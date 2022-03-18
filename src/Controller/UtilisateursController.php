<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilFormType;
use App\Repository\EtatRepository;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateursController extends AbstractController
{
    #[Route('/admin/utilisateurs', name: 'utilisateurs')]
    public function afficherUtilisateurs(EntityManagerInterface $entityManager, MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
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

        /*
         * Créer un nouvel utilisateur
         */
        $user = new User();
        $user->setActif(true);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                'bn4&BPDW&3u7TiChF3*v'
            )
        );
        $form = $this->createFormBuilder()
            ->add('pseudo', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('site', EntityType::class, array(
                'class' => 'App\Entity\Site',
                'choice_label' => 'nom'
            ))
            ->add('ajouter', SubmitType::class, array(
                'label' => 'Ajouter',
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user
                ->setPseudo($data['pseudo'])
                ->setNom($data['nom'])
                ->setPrenom($data['prenom'])
                ->setEmail($data['email'])
                ->setSite($data['site']);

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(Address::create('sortir.com <sortir@no-reply.com>'))
                ->to(Address::create($user->getPrenom() .'<'.$user->getEmail().'>'))
                ->subject('Création de votre compte')
                ->htmlTemplate('pages/emails/nouveauCompte.html.twig')
                ->context([
                    'user' => $user,
                ])
            ;
            $mailer->send($email);

            $this->addFlash(
                'notice',
                "L'utilisateur ".$user->getPseudo().' a été créé avec succès !'
            );
            return $this->redirectToRoute('utilisateurs', []);
        }

        return $this->render('pages/administration/utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
            'utilisateurForm' => $form->createView(),
            'utilisateurFiltreForm' => $formFiltre->createView()
        ]);
    }

    #[Route('/admin/utilisateurs/{id}', name: 'utilisateur_actif_inactif')]
    public function activerUtilisateur(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository, EtatRepository $etatRepository, MailerInterface $mailer): Response
    {
        $user = $userRepository->find($id);
        $user->setActif(!$user->getActif());

        $entityManager->persist($user);
        $entityManager->flush();

        if (!$user->getActif()) {
            /*
             * Annulation des sorties dont $user est organisateur
             * & annulation de son inscription à des sorties
             */
            foreach ($user->getSortiesOrganisateur() as $sortie) {
                if ( $sortie->getEtat()->getLibelle() =='Clôturée' ) {
                    $sortie->setMotifAnnulation('Annulation par action administrateur.');
                    $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
                    $sortie->setEtat($etat);

                    /*
                     * Notification des participants par email
                     */
                    $to = array();
                    foreach ($sortie->getSortiesParticipants() as $participant) {
                        $to[] = Address::create($participant->getPrenom() .'<'.$participant->getEmail().'>');
                    }
                    $email = (new TemplatedEmail())
                        ->from(Address::create('sortir.com <sortir@no-reply.com>'))
                        ->to(...$to)
                        ->subject('Sortie Annulée : '.$sortie->getNom())
                        ->htmlTemplate('pages/emails/annulationSortie.html.twig')
                        ->context([
                            'sortie' => $sortie,
                        ])
                    ;
                    $mailer->send($email);

                    $entityManager->persist($sortie);
                    $entityManager->flush();
                }
            }
            foreach ($user->getParticipant() as $sortie) {
                $sortie->removeSortiesParticipant($user);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        /*
        * L'utilisateur est prévenu par email de l'état de son compte
        */
        $etatCompte = $user->getActif() ? "réactivé" : 'désactivé';
        $email = (new TemplatedEmail())
            ->from(Address::create('sortir.com <sortir@no-reply.com>'))
            ->to(Address::create($user->getPrenom() .'<'.$user->getEmail().'>'))
            ->subject('Votre compte a été '.$etatCompte)
            ->htmlTemplate('pages/emails/etatCompte.html.twig')
            ->context([
                'suppr' => false,
                'user' => $user,
            ])
        ;
        $mailer->send($email);

        $this->addFlash(
            'notice',
            $user->getActif() ?
                "L'utilisateur ".$user->getPseudo()." a été activé !"
                :
                "L'utilisateur ".$user->getPseudo()." a été désactivé !"
        );

        return $this->redirectToRoute('utilisateurs', []);
    }

    #[Route('/admin/utilisateurs/supprimer/{id}', name: 'utilisateur_suppr')]
    public function supprimerUtilisateur(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository, ResetPasswordRequestRepository $passwordRepository, MailerInterface $mailer): Response
    {
        $user = $userRepository->find($id);

        /*
         * Annulation + Suppression des sorties dont $user est organisateur
         * & annulation de son inscription à des sorties
         * & suppression de son historique de changement de mdp
         */
        foreach ($user->getSortiesOrganisateur() as $sortie) {
            if ( $sortie->getEtat()->getLibelle() =='Clôturée' ) {
                $sortie->setMotifAnnulation('Annulation par action administrateur.');

                /*
                 * Notification des participants par email
                 */
                $to = array();
                foreach ($sortie->getSortiesParticipants() as $participant) {
                    $to[] = Address::create($participant->getPrenom() .'<'.$participant->getEmail().'>');
                }
                $email = (new TemplatedEmail())
                    ->from(Address::create('sortir.com <sortir@no-reply.com>'))
                    ->to(...$to)
                    ->subject('Sortie Annulée : '.$sortie->getNom())
                    ->htmlTemplate('pages/emails/annulationSortie.html.twig')
                    ->context([
                        'sortie' => $sortie,
                    ])
                ;
                $mailer->send($email);

                $entityManager->remove($sortie);
                $entityManager->flush();
            }
        }
        foreach ($user->getParticipant() as $sortie) {
            $sortie->removeSortiesParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        $changements = $passwordRepository->findBy(['user' => $user]);
        foreach ($changements as $changement) {
            $passwordRepository->remove($changement);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        /*
        * L'utilisateur est prévenu par email de l'état de son compte
        */

        $email = (new TemplatedEmail())
            ->from(Address::create('sortir.com <sortir@no-reply.com>'))
            ->to(Address::create($user->getPrenom() .'<'.$user->getEmail().'>'))
            ->subject('Votre compte a été supprimé')
            ->htmlTemplate('pages/emails/etatCompte.html.twig')
            ->context([
                'suppr' => true,
                'user' => $user,
            ])
        ;
        $mailer->send($email);

        $this->addFlash(
            'notice',
            "L'utilisateur ".$user->getPseudo()." a été supprimé !"
        );

        return $this->redirectToRoute('utilisateurs', []);
    }
}
