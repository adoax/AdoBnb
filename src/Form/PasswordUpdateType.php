<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfig('Ancien mot de passe', 'Ancien Mot de passe'))
            ->add('newPassword', RepeatedType::class,  [
                'type' => PasswordType::class,
                'invalid_message' => 'Votre nouveau mot de passe, ne sont pas identique',
                'first_options' =>  ['label' => 'Nouveau mot de passe', 'attr' => [
                    'placeholder' => 'Votre nouveau mot de passe'
                ]],
                'second_options' => ['label' => 'Repeter votre nouveau mot de passe', 'attr' => [
                    'placeholder' => 'Repeter votre nouveau mot de passe'
                ]]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
