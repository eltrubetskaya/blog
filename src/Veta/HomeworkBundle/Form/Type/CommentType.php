<?php

namespace Veta\HomeworkBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veta\HomeworkBundle\Entity\Comment;
use Veta\HomeworkBundle\Entity\Post;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class, ['label'=>' '])
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => true,
                'label_attr'=> [
                    'class' => 'hidden'
                ],
                'attr'=> [
                    'class' => 'hidden'
                ]
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'veta_homeworkbundle_comment';
    }
}
