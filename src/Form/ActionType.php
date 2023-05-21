<?php

namespace App\Form;

use App\Entity\Action;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('event_action', TextType::class, [
            'label' => 'Action',
        ])
        ->add('text', TextType::class, [
            'required' => false,
        ])
        ->add('option_yes', IntegerType::class, [
            'required' => false,
        ])
        ->add('option_no', IntegerType::class, [
            'required' => false,
        ])
        ->add('option_object', IntegerType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
