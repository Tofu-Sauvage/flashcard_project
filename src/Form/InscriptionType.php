<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            // ->add('roles')
            ->add('password', PasswordType::class)
            ->add('name', TextType::class)
            // ->add('created_at', DateTimeType::class)
            ->add('image', FileType::class, ['mapped' => false, 'required' => false])
            // ->add('favorites')
            ->add('language_native', EntityType::class, 
            ['class'=>Language::class, 
            'choice_label' => 'name',
            'multiple' => 'false',
            'expanded' => 'false'
            ])
            ->add('language_learn', EntityType::class, 
            ['class'=>Language::class, 
            'choice_label' => 'name',
            'multiple' => 'false',
            'expanded' => 'false'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
