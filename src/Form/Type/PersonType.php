<?php

namespace App\Form\Type;

use App\Controller\TimezoneHelper;
use App\Entity\Person;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $placenames = $options['attr'];
        asort($placenames);
        for ($i = 0; $i < count($placenames); ++$i)
        {
            if (strpos($placenames[$i], "(!)") !== false)
            {
                $placenames[$i] = substr($placenames[$i], 3);
                break;
            }
        }
        $timezones = TimezoneHelper::getCustomFlippedTimeZones($options['data']->getTimezone());
        $builder
            ->add('fullname', TextType::class, [
                'label' => "Full name: ",
                'attr' => ['class' => 'form-input']
            ])
            ->add('birthdate', DateTimeType::class, [
                'widget' =>'single_text',
                'required' => true,
                'label' => "Birth date: ",
                'attr' => ['class' => 'form-control'],
                'html5' => false,
                'format' => 'yyyy-MM-ddTHH:mm'
            ])
            ->add('timezone', TimezoneType::class, [
                'choice_loader' => null,
                'choices' => $timezones,
                'choice_label' => function($value) {
                    return TimezoneHelper::toGmtOffset($value) . " " . $value;
                },
                'label' => 'Timezone: ',
                'attr' => ['class' => 'form-input', 'title' => '* Be carefull: pick correct timezone by numbers, not by names - they represent modern state of things']
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'query_builder' => function (PlaceRepository $er) {
                  return $er->createQueryBuilder('p')
                      ->orderBy('p.placename', 'ASC');
                },
                'choice_label' => function(Place $place){
                   return $place->getPlacename() .
                   ' (' . substr($place->getLongitude(), 0, 7) .
                   ', ' . substr($place->getLatitude(), 0, 6) . ')';
                },
                 'label' => "Birth place: ",
                'attr' => ['class' => 'form-input'],
                'mapped' => false
            ])
            ->add('newplace', SubmitType::class, [
                'label' => 'Another place',
                'attr' => ['class' => 'fake_button']
            ]);
        if ($options['data']->getId() > 0)
            $builder->add('newperson', SubmitType::class, [
                'label' => 'Another person',
                'attr' => ['class' => 'fake_button_right']
            ]);
        $builder->add('save', SubmitType::class, [
                'label' => ($options['data']->getId() > 0 ? 'Update' :'Add') . ' the person',
                'attr' => ['class' => 'button-submit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
