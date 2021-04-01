<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReserverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nom', TextType::class, array('required' => true))
            ->add('prenom', TextType::class, array('required' => true))
            ->add('nbrplace',TextType::class, array('required' => true))
            ->add('event',EntityType::class,
                [
                    'required'=> true,
                    'multiple'=> true,
                    'placeholder' => 'Sélectionner le nom de lévenement ',
                    'class' => Event::class,
                    'choice_label' => 'title'


                ]
            )

        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
