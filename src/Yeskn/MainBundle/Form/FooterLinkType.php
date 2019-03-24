<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-24 23:45:43
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\FooterLink;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FooterLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => '文本',
            'required' => true
        ]);

        $builder->add('link', TextType::class, [
            'label' => '链接',
            'required' => true,
        ]);

        $builder->add('priority', IntegerType::class, [
            'label' => '权重',
            'required' => true,
        ]);

        $builder->add('isPjax', CheckboxType::class, [
            'label' => '是否站内',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FooterLink::class
        ]);
    }
}
