<?php

namespace Yeskn\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function  buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('title',null,array('attr' => array('autofocus' => true), 'label' => '标题',))
           ->add('content',null,array('attr' => array('rows' => 15), 'label' => '内容','required'=>false))
//           ->add('categories',CollectionType::class,array(
//               'label' => '分类',
//               'entry_type' => CategoryType::class,
//                'allow_add' => true,
//               'by_reference' => false,
//           ))
//           ->add('tags',CollectionType::class,array(
//               'label' => '标签' ,
//               'entry_type' => TagType::class ,
//               'allow_add' => true,
//               'by_reference' => false,
//           ))
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
           'data_class' => 'Yeskn\BlogBundle\Entity\Post',
       ));
    }
}