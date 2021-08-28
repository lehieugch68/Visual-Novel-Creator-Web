<?php

namespace App\Form;

use App\Entity\GameStoryScene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\GameImage;
use App\Entity\GameSound;
use App\Form\GameImageType;
use App\Form\GameSoundType;

class GameStorySceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Text'
                ]
            ])
            ->add('contextorder', null, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Order'
                ]
            ])
            ->add('talker', null, [
                'attr' => [
                    'placeholder' => 'Talker'
                ]
            ])
            ->add('create', SubmitType::class, [
                'attr' => [
                    'class' => 'submit-btn'
                ],
            ])
            ->add('talkerimg', GameImageType::class, [
                'mapped' => false,
            ])
            ->add('talkerimgselect', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => GameImage::class,
                'choices' => $options['user']->getGameImages(),
                'choice_label' => 'filename',
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => function ($object) {
                    return [ 'url' => $object->getUrl() ];
                }
            ])
            ->add('characterimg', GameImageType::class, [
                'mapped' => false,
            ])
            ->add('characterimgselect', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => GameImage::class,
                'choices' => $options['user']->getGameImages(),
                'choice_label' => 'filename',
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => function ($object) {
                    return [ 'url' => $object->getUrl() ];
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameStoryScene::class,
            'user' => false,
        ]);
    }
}
