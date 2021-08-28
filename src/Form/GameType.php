<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Account;
use App\Entity\GameImage;
use App\Form\GameImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description'
                ]
            ])
            ->add('cover', GameImageType::class, [
                'mapped' => false,
            ])
            ->add('imageselect', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => GameImage::class,
                'choices' => $options['user']->getGameImages(),
                'choice_label' => 'filename',
                'data' => $options['currentcover'],
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => function ($object) {
                    return [ 'url' => $object->getUrl() ];
                }
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
            'data_class' => Game::class,
            'user' => false,
            'currentcover' => false,
        ]);
    }
}
