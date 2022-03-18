<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('pseudo')
            ->add('photo', FileType::class, array(
                'multiple' => false,
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'maxWidth' => 200,
                        'maxHeight' => 200,
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Seuls les formats png, jpg, jpeg sont acceptés',
                    ])
                ],

            ))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ])
            ->add('site', EntityType::class, array(
                'class' => 'App\Entity\Site',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un Site...'
            ))
            ->add('enregistrer', SubmitType::class, array(
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-lg-1',
                ],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
