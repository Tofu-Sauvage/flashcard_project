<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\DeckType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class)
            ->add('answer', TextType::class)
            ->add('tense', TextType::class)
            ->add('mood', TextType::class)
            ->add('sentence1', TextType::class)
            ->add('sentence2', TextType::class)
            ->add('image', FileType::class, ['required' => false, 'mapped' => false])
            ->add('created_at', DateType::class, ['widget' => 'single_text'])
            ->add('author', TextType::class)
            ->add('category', EntityType::class, [
                "class" => Category::class,
                "choice_label" => 'name',
                "multiple" => false,
                "expanded" => true
            ])
            /*->add('decks', DeckType::class dans un tableau)*/
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
