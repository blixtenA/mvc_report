<?php

namespace App\Form;

use App\Entity\ObjectByRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectByRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room_id', IntegerType::class)
            ->add('object_id', IntegerType::class)
            ->add('sequence', IntegerType::class)
            ->add('position_x', IntegerType::class)
            ->add('position_y', IntegerType::class)
            ->add('position_z', IntegerType::class)
            ->add('width', IntegerType::class, [
                'required' => false,
            ])
            ->add('height', IntegerType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjectByRoom::class,
        ]);
    }
}
