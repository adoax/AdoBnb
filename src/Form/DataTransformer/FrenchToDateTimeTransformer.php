<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($dateAsArray)
    {
        if ($dateAsArray === null) {
            return "";
        }
        return $dateAsArray->format('d/m/Y');
    }

    public function reverseTransform($dateAsString)
    {
        if ($dateAsString === null) {
            throw new TransformationFailedException("Vous devez fournir un date");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $dateAsString);

        if ($date === false) {

            throw new TransformationFailedException("Le format de la date de correspond pas");
        }

        return $date;
    }
}
