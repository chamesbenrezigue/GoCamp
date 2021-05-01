<?php

namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name')
            ->add('email')
            ->add('first_name')
            ->add('firstName')
            ->add('lastName')
            ->add('roles' , ChoiceType::class,[
                'choices'=>[
                    'User'=>'ROLE_USER',
                    'Admin'=>'ROLE_ADMIN'
                ],
                'expanded'=>true,
                'multiple'=>true,
                'label'=>'Roles'
            ])

            ->add('email',EmailType::class)
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
