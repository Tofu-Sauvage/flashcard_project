<?php

namespace App\Form;

use App\Entity\Deck;
use App\Entity\Language;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            // ->add('created_at', DateType::class)
            ->add('public', CheckboxType::class, ['required' => false])
            // ->add('tags', TextType::class) // A jouter plus tard
            // ->add('author', TextType::class)
            // ->add('fans', TextType::class)
            // ->add('cards', EntityType::class, [
            //     "class" => Card::class,
            //     "choice_label" => 'name',
            //     "multiple" => false,
            //     "expanded" => true
            // ])
            ->add('langague_learn', EntityType::class, [
                    "class" => Language::class,
                    "choice_label" => 'name',
                    "multiple" => false,
                    "expanded" => true 
                    ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deck::class,
        ]);
    }
}
