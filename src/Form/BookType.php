<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Title',
            ])
            ->add('ISBN', TextType::class, [
                'required' => true,
                'label' => 'ISBN',
            ])
            ->add('author', TextType::class, [
                'required' => true,
                'label' => 'Author',
            ])
            ->add('image', TextType::class, [
                'required' => true,
                'label' => 'Image',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
