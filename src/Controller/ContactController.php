<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController

{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('email', EmailType::class)
            ->add('objet', TextType::class)
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez votre message ici'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData('email');

            $email = (new TemplatedEmail())
                ->from(Address::create($data['nom'].' <'.$data['email'].'>'))
                ->to('sortir@no-reply.fr')
                ->subject($data['objet'])
                ->htmlTemplate('pages/emails/contactForm.html.twig')
                ->context([
                    'from' => $data['nom'],
                    'message' => $data['message'],
                ])
            ;
            $mailer->send($email);

            $this->addFlash(
                'notice',
                'Votre message a bien été envoyé, il sera traité le plus rapidement possible.'
            );
        }

        return $this->render('pages/contact/contact.html.twig', [
            'contactForm' => $form->createView()
        ]);

    }

}
