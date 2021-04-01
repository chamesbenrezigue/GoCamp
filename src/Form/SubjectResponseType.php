<?php

namespace App\Form;

use App\Entity\SubjectResponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SubjectResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Content', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'write your comment...',
                    'rows' => '4'
                ],
                'label' => false
            ])
            ->add('Author')
            ->add('Subject')
            ->add('Typeresponse',ChoiceType::class,array(
                'choices'=>array(
                    'audio'=>'audio',
                    'video'=>'video',
                    'text'=>'text',
                    'image'=>'image'
                )
            ))
            ->add('imageFile',VichImageType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubjectResponse::class,
        ]);
    }
}
