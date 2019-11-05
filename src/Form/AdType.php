<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{

    /**
     * Permet d'avoir la config de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfig($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfig("Titre", "Mettez le titre de l'annonce"))
            ->add('price', MoneyType::class, $this->getConfig("PRIX", "Indiquer le prix pour une nuit") + [
                "currency" => 'EUR'
            ])
            ->add('intro', TextType::class, $this->getConfig("Introduction", "Donnez une introduction globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfig("Description", "Donnez une description qui donne envie de venir chez vous"))
            ->add('coverImage', UrlType::class, $this->getConfig("Url de l'image principale", "Donnez une immage qui donne envie "))
            ->add('rooms', IntegerType::class, $this->getConfig("Nombre de chambre", "Le nombres de chambre disponible"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
