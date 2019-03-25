<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-17 23:15:08
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Page;
use Yeskn\MainBundle\Form\Type\TinyHtmlTextareaType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => '标题',
        ]);

        $builder->add('uri', TextType::class, [
            'label' => '路径'
        ]);

        $builder->add('status', CheckboxType::class, [
            'label' => '启用',
            'required'=>false
        ]);

        $builder->add('content', TinyHtmlTextareaType::class, [
            'label' => '内容',
            'required' => true,
            'height' => 300
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class
        ]);
    }
}
