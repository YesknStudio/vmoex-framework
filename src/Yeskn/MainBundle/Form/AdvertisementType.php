<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 21:19:45
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Advertisement;
use Yeskn\MainBundle\Form\Type\TinyHtmlTextareaType;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => '标题',
            'required' => true,
        ]);
        $builder->add('content', TinyHtmlTextareaType::class, [
            'label' => '内容',
            'required' => true,
        ]);
        $builder->add('type', ChoiceType::class, [
            'label' => '类型',
            'required' => true,
            'choices' => [
                'html' => 'html',
            ]
        ]);
        $builder->add('location', ChoiceType::class, [
            'label' => '位置',
            'required' => true,
            'choices' => [
                '边栏1' => 'sidebar1',
                '边栏2' => 'sidebar2',
                '底部1' => 'footer1'
            ]
        ]);

        $builder->add('enable', CheckboxType::class, [
            'label' => '启用',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
