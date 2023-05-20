<?php

namespace App\Form;

use App\Entity\EventByObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventByObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('objectId', IntegerType::class)
        ->add('eventId', IntegerType::class)
        ->add('location', IntegerType::class)
        ->add('action1', IntegerType::class, [
            'required' => false,
        ])
        ->add('action2', IntegerType::class, [
            'required' => false,
        ])
        ->add('action3', IntegerType::class, [
            'required' => false,
        ])
        ->add('action4', IntegerType::class, [
            'required' => false,
        ])
        ->add('action5', IntegerType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventByObject::class,
        ]);
    }
}
