<?php

namespace App\Form;

use App\Entity\GameScene;
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

class GameSceneUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sceneorder', null, [
                'attr' => [
                    'placeholder' => 'Order'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('create', SubmitType::class, [
                'attr' => [
                    'class' => 'submit-btn'
                ],
            ])
            ->add('bg', GameImageType::class, [
                'mapped' => false,
            ])
            ->add('bgm', GameSoundType::class, [
                'mapped' => false,
            ])
            ->add('imageselect', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => GameImage::class,
                'choices' => $options['user']->getGameImages(),
                'choice_label' => 'filename',
                'data' => $options['currentbg'],
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => function ($object) {
                    return [ 'url' => $object->getUrl() ];
                }
            ])
            ->add('soundselect', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => GameSound::class,
                'choices' => $options['user']->getGameSounds(),
                'data' => $options['currentbgm'],
                'choice_label' => 'filename',
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GameScene::class,
            'user' => false,
            'currentbg' => false,
            'currentbgm' => false,
        ]);
    }
}
