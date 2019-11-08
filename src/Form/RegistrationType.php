<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',  TextType::class, $this->getConfig('Prenom', 'Votre prenom ..'))
            ->add('lastName', TextType::class, $this->getConfig('Nom', 'Votre nom de famille'))
            ->add('email', EmailType::class, $this->getConfig('Email', 'Votre adresse email'))
            ->add('picture', UrlType::class, $this->getConfig('Photo de prfil', 'Veuillez indqiue votre photo de profil'))
            ->add('hash', RepeatedType::class,  [
                'type' => PasswordType::class,
                'invalid_message' => 'Votre mot de passe, ne sont pas identique',
                'first_options' =>  ['label' => 'Mot de passe', 'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]],
                'second_options' => ['label' => 'Repeter votre mot de passe', 'attr' => [
                    'placeholder' => 'Repeter votre mot de passe'
                ]]
            ])
            ->add('intro', TextType::class, $this->getConfig('Introduction', 'Presentation en quelque mot'))
            ->add('description', TextareaType::class, $this->getConfig('Description', 'Presentation de vous en detaille'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
