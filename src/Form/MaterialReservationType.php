<?php

namespace App\Form;

use App\Entity\MaterialReservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MaterialReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateStart', DateType::class, array(
                "data"=> new \DateTime(),
                 "widget" => 'single_text',
                "format" => 'yyyy-MM-dd'
            ))
            ->add('dateEnd',DateType::class, array(
                "data"=> new \DateTime(),
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MaterialReservation::class,
            'method' => 'get',
            'csrf_protection'=>false
        ]);
    }
}
