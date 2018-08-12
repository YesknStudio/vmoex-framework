<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\WebBundle\Entity\Tab;
use Yeskn\WebBundle\Entity\Tag;
use Yeskn\WebBundle\Entity\User;

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
           'data_class' => 'Yeskn\WebBundle\Entity\Post',
       ));
    }
}