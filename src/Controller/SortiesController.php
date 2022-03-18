<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use App\Form\SortieFormType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class SortiesController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/sortie/{id}', name: 'sortie_details')]
    public function afficherSortie(int $id, Request $request, SortieRepository $sortieRepository, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $form = $this->createForm(SortieFormType::class, $sortie);
        return $this->render('pages/afficherSortie.html.twig', [
            'sortie' => $sortie,
            'sortieForm' => $form->createView(),
        ]);
    }

    #[Route('/sortie/modifier/{id}', name: 'sortie_modifier')]
    public function modifierSortie(int $id, Request $request, SortieRepository $sortieRepository, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = $sortieRepository->find($id);
        $villes = $villeRepository->findAll();

        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'La sortie N°'.$sortie->getId().' a été mise à jour avec succès !'
            );

            return $this->redirectToRoute('sortie_modifier', ['id' => $sortie->getId()]);
        }

        return $this->render('pages/sortie.html.twig', [
            'sortie' => $sortie,
            'villes' => $villes,
            'sortieForm' => $form->createView(),
        ]);
    }

    #[Route('/sortie', name: 'sortie_creer')]
    public function creerSortie(Request $request, EtatRepository $etatRepository, VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $villes = $villeRepository->findAll();

        /*
         * gestion de la création
         */
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            $site = $user->getSite();
            $etat = $form->get('publier')->isClicked() ?
                $etatRepository->findBy(['libelle' => 'Ouverte']) : $etatRepository->findBy(['libelle' => 'Créée']);
            $sortie->setEtat($etat[0])->setSite($site)->setOrganisateur($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'La sortie '.$sortie->getNom().' a été '.strtolower($sortie->getEtat()->getLibelle()).' avec succès !'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('pages/sortie.html.twig', [
            'villes' => $villes,
            'sortieForm' => $form->createView(),
        ]);
    }

    #[Route('/sortie/annuler/{id}', name: 'sortie_annuler')]
    public function annulerSortie(Request $request, int $id, SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $sortie = $sortieRepository->find($id);
        $form = $this->createFormBuilder()
            ->add('motifAnnulation', TextareaType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $sortie->setMotifAnnulation($data['motifAnnulation']);
            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            $sortie->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'La sortie N°'.$sortie->getId().' a été annulée !'
            );

            /*
             * Notification des participants par email
             */
            $to = array();
            foreach ($sortie->getSortiesParticipants() as $participant) {
                $to[] = Address::create($participant->getPrenom() .'<'.$participant->getEmail().'>');
            }
            if ( sizeof($to) > 0) {
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
            }

            return $this->redirectToRoute('sortie_details', ['id' => $sortie->getId()]);
        }

        return $this->render('pages/annulerSortie.html.twig', [
            'sortie' => $sortie,
            'sortieForm' => $form->createView(),
        ]);
    }

    #[Route('/sortie/supprimer/{id}', name: 'sortie_suppr')]
    public function supprimerSortie(int $id, EntityManagerInterface $entityManager, SortieRepository $sortieRepository) {
        $sortie = $sortieRepository->find($id);

        $entityManager->remove($sortie);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'La sortie N°'.$id.' a été supprimée !'
        );

        return $this->redirectToRoute('home');
    }

    #[Route('/sortie/{id}/{nouvelEtat}', name: 'sortie_etat')]
    public function updateEtatSortie(int $id, string $nouvelEtat,  SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $etat = $etatRepository->findBy(['libelle' => $nouvelEtat]);
        $sortie->setEtat($etat[0]);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Votre sortie '.$sortie->getNom().' est désormais '. strtolower($nouvelEtat) .' !'
        );

        return $this->redirectToRoute('home');
    }

    #[Route('/sortie/{action}/{id}/{userId}', name: 'sortie_inscription_desistement')]
    public function inscription(int $id, int $userId, string $action, SortieRepository $sortieRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $sortie = $sortieRepository->find($id);
        $user = $userRepository->find($userId);

        $flashMesssage = '';
        switch ($action) {
            case 'inscription':
                $sortie->addSortiesParticipant($user);
                $flashMesssage = 'Vous êtes inscrit(e) à la sortie '. $sortie->getNom().' !';
                break;
            case 'desistement':
                $sortie->removeSortiesParticipant($user);
                $flashMesssage = 'Vous êtes désinscrit(e) de la sortie '. $sortie->getNom().' !';;
                break;
        }
        $entityManager->persist($sortie);
        $entityManager->flush();


        $this->addFlash(
            'notice',
            $flashMesssage
        );

        return $this->redirectToRoute('home');
    }

    #[Route('/sortie/ajax', name: 'ajaxform')]
    public function ajaxAction(Request $request) {
        $responseArray[] = array();
        if ($request->isXmlHttpRequest() ) {
            $id= $_POST['id'] ;
            $lieux = $this->getDoctrine()
                ->getRepository(Lieu::class)
                ->findBy(['ville' => $id]);

            foreach($lieux as $lieu) {
                $responseArray[] = array(
                    'id' => $lieu->getId(),
                    'nom' => $lieu-> getNom(),
                );
            }
        }
        return new JsonResponse($responseArray);
    }

}
