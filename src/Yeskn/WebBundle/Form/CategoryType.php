<?php
/**
 * This file is part of project vmoex.
 * User: Jake
 * Date: 2016/6/23
 * Time: 14:07
 */

namespace Yeskn\WebBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextareaType::class,array('label' => '分类'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yeskn\WebBundle\Entity\Category'
        ));
    }
}