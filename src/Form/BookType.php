<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre du livre',])
            ->add('author', TextType::class, ['required' => true,])
            ->add('style', ChoiceType::class,[
                'choices' => [
                    'Thriller' => 'thriller',
                    'Policier' => 'policier',
                    'Autobiographie' => 'autobio',
                    'Fantastique' => 'fantastique',
                    'Romantique' => 'romantique',
        ]
        ])
            ->add('nbPages')
            ->add('inStock')
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
