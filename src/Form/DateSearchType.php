<?php

namespace App\Form;
use App\Entity\DateSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateS',DateType::class, array(
        "data"=> new \DateTime(),
        "widget" => 'single_text',
        "format" => 'yyyy-MM-dd'
    ))
            ->add('dateE',DateType::class, array(
        "data"=> new \DateTime(),
        "widget" => 'single_text',
        "format" => 'yyyy-MM-dd'
    ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DateSearch::class,
        ]);
    }
}












