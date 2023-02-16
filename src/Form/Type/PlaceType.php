<?php

namespace App\Form\Type;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placename', HiddenType::class)
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => ($options['data']->getId() > 0 ? 'Update' :'Add') . ' the place',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Place::class,
        ]);
    }
}