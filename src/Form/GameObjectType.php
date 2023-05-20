<?php

namespace App\Form;

use App\Entity\GameObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class GameObjectType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('id', TextType::class, [
                'disabled' => true,
            ])
            ->add('image', TextType::class)
            ->add('name', TextType::class)
            ->add('clickable', CheckboxType::class, [
                'required' => false,
            ])
            ->add('image2', TextType::class, [
                'required' => false,
            ])
            ->add('effect', TextType::class, [
                'required' => false,
            ]);

    }

    public function transformOptions(FormEvent $event)
    {
        $data = $event->getData();
        $options = explode("\n", $data['options']);
        $data['options'] = [];

        foreach ($options as $option) {
            $keyValue = explode(':', $option, 2);
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1] ?? '');

            if (!empty($key)) {
                $data['options'][$key] = $value;
            }
        }

        $event->setData($data);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameObject::class,
        ]);
    }

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        return $value;
    }
}
