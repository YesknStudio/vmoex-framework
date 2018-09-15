<?php

namespace Yeskn\MainBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Tab;
use Yeskn\MainBundle\Entity\Tag;
use Yeskn\MainBundle\Entity\User;

class PostType extends AbstractType
{
    public function  buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('title',null, array('attr' => array('autofocus' => true), 'label' => '标题',))
           ->add('content',null, array('attr' => array('rows' => 15), 'label' => '内容','required'=>false))
           ->add('tags',EntityType::class, [
               'class' => Tag::class ,
               'choice_label' => 'name',
               'choice_value' => 'id',
               'label' => '标签' ,
               'multiple' => true
           ])
           ->add('tab', EntityType::class, [
                'class' => Tab::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => '板块'
           ])
           ->add('author', EntityType::class, [
               'class' => User::class,
               'choice_label' => 'nickname',
               'choice_value' => 'id',
               'label' => '作者'
           ])
           ->add('views', TextType::class, ['label' => '点击'])
           ->add('isTop',CheckboxType::class,array('label' => '置顶','required'=>false))
           ->add('cover',TextType::class,array('label' => '封面图'))
       ;
    }

    /**
     * @inheritdoc
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array(
           'data_class' => 'Yeskn\MainBundle\Entity\Post',
       ));
    }
}