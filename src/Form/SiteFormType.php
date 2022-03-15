<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, array(
                'mapped' => false,
            ))
            ->add('nom')
            ->add('ajouter', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-primary col-lg-auto',
                ],
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
