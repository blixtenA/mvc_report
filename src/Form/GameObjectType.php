<?php

namespace App\Form;

use App\Entity\GameObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GameObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('id', TextType::class, [
                'disabled' => true,
            ])
            ->add('image', TextType::class)
            ->add('name', TextType::class)
            ->add('image2', TextType::class, [
                'required' => false,
            ])
            ->add('effect', TextType::class, [
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameObject::class,
        ]);
    }

}
