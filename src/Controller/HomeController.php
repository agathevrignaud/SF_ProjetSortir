<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Psr\Log\LoggerInterface;


class HomeController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function listeSorties(Request $request, SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        $user->getRoles();

        $form = $this->createFormBuilder()
            ->add('site', EntityType::class, array(
                'class' => 'App\Entity\Site',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un site...',
                'required' => false,
            ))
            ->add('nom', TextType::class, array(
                'label' => 'Le nom contient :',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom de la sortie',
                )
            ))
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker align-middle ps-2',
                    'placeholder' => 'jj/mm/aaaa',
                    'style' => 'width: 100px',
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker align-middle ps-2',
                    'placeholder' => 'jj/mm/aaaa',
                    'style' => 'width: 100px'
                ],
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'    => "Sorties dont je suis l'organisateur/trice",
                'required' => false,
                'attr' => [
                    'class' => 'align-middle ps-2',
                ],
            ])
            ->add('estInscrit', CheckboxType::class, [
                'label'    => "Sorties auxquelles je suis inscrit(e)",
                'required' => false,
                'attr' => [
                    'class' => 'align-middle ps-2',
                ],
            ])
            ->add('estNonInscrit', CheckboxType::class, [
                'label'    => "Sorties auxquelles je ne suis pas inscrit(e)",
                'required' => false,
                'attr' => [
                    'class' => 'align-middle ps-2',
                ],
            ])
            ->add('estPassee', CheckboxType::class, [
                'label'    => "Sorties passées",
                'required' => false,
                'attr' => [
                    'class' => 'align-middle ps-2',
                ],
            ])
            ->add('userId', HiddenType::class, [
                'data' => $user->getId(),
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty(array_filter($data, function($i) { return $i; }))) {
                $sorties = $sortieRepository->findSortieByFilter($data);
            } else {
                $sorties = $sortieRepository->findAll();
            }
        } else {
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('pages/home.html.twig', [
            "user" => $user,
            'sorties' => $sorties,
            'etatLabels' => ['Créée' => 'primary', 'Ouverte' => 'info', 'Clôturée' => 'warning', 'En cours' => 'success', 'Passée' => 'secondary', 'Annulée' => 'danger' ],
            'sortieFiltreForm' => $form->createView()
        ]);
    }

}
