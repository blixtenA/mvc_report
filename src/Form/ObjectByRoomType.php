<?php

namespace App\Form;

use App\Entity\ObjectByRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectByRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('room_id', IntegerType::class)
            ->add('object_id', IntegerType::class)
            ->add('sequence', IntegerType::class)
            ->add('position_x', IntegerType::class)
            ->add('position_y', IntegerType::class)
            ->add('position_z', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ObjectByRoom::class,
        ]);
    }
}
