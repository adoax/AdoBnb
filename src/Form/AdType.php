<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{

    /**
     * Permet d'avoir la config de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $option
     * @return array
     */
    private function getConfig($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfig("Titre", "Mettez le titre de l'annonce"))
            ->add('slug', TextType::class, $this->getConfig("URL (non obligatoire)", "Mettez l'ur souhaiter (automatique)", [
                'required' => false
            ]))
            ->add('price', MoneyType::class, $this->getConfig("PRIX", "Indiquer le prix pour une nuit") + [
                "currency" => 'EUR'
            ])
            ->add('intro', TextType::class, $this->getConfig("Introduction", "Donnez une introduction globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfig("Description", "Donnez une description qui donne envie de venir chez vous"))
            ->add('coverImage', UrlType::class, $this->getConfig("Url de l'image principale", "Donnez une immage qui donne envie "))
            ->add('rooms', IntegerType::class, $this->getConfig("Nombre de chambre", "Le nombres de chambre disponible"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
