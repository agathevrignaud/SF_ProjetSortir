<?php

namespace App\Form;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',DateType::class, array(
                'label' => "Date et heure de la sortie :",
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy hh:mm',
                'required' => false,
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker align-middle ps-2',
                    'placeholder' => 'jj/mm/aaaa h:m',
                ],
            ))
            ->add('dateLimiteIncription',DateType::class, array(
                'label' => "Date limite d'inscription :",
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker align-middle ps-2',
                    'placeholder' => 'jj/mm/aaaa',
                ],
            ))
            ->add('nbInscriptionsMax',TextType::class, array(
                'label' => 'Nombre de places :'
            ))
            ->add('duree',IntegerType::class, array(
                'attr' => [
                    'min' => 0,
                    'max' => 240,
                    'step' => 10
                ]
            ))
            ->add('infoSortie',TextareaType::class, array(
                'label' => 'Description et infos :'
            ))
            ->add('lieu', EntityType::class, array(
                'class' => 'App\Entity\Lieu',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un lieu...'
            ))
            ->add('enregistrer', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-lg-1',
                ],
            ))
            ->add('publier', SubmitType::class, array(
                'label' => 'Publier la sortie',
                'attr' => [
                    'class' => 'btn btn-outline-success col-lg-auto',
                ],
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
