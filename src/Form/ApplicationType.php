<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Permet d'avoir la config de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $option
     * @return array
     */
    protected function getConfig($label, $placeholder, $options = [])
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}