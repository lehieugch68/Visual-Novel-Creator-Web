<?php

namespace App\Form;

use App\Entity\GameIntro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameIntroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Content'
                ]
            ])
            ->add('introorder', null, [
                'attr' => [
                    'placeholder' => 'Order'
                ]
            ])
            ->add('create', SubmitType::class, [
                'attr' => [
                    'class' => 'submit-btn'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameIntro::class,
        ]);
    }
}
