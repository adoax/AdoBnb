<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transform;

    public function __construct(FrenchToDateTimeTransformer $transform)
    {
        $this->transform = $transform;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfig("Heure de d'arrivé", "Veuillez mettre votre date au quel vous comptez arrivez"))
            ->add('endDate', TextType::class, $this->getConfig("Heure de départ", "Veuillez mettre votre date au quel vous comptez partir"))
            ->add('comment', TextareaType::class, $this->getConfig(false, "Si vous avez un commentaire hesister pas en faire part !", [
                'required' => false
            ]));

            $builder->get('startDate')->addModelTransformer($this->transform);
            $builder->get('endDate')->addModelTransformer($this->transform);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
