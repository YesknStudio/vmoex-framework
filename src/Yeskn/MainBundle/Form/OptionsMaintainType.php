<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-26 21:09:45
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Yeskn\Support\ParameterBag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionsMaintainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('maintain_enable', CheckboxType::class, [
            'label' => '启用',
            'required' => false,
        ]);
        $builder->add('maintain_start', DateTimeType::class, [
            'label' => '开始时间',
            'input' => 'string',
            'widget' => 'single_text',
            'with_seconds' => true,
            'required' => true
        ]);
        $builder->add('maintain_stop', DateTimeType::class, [
            'label' => '结束时间',
            'input' => 'string',
            'widget' => 'single_text',
            'with_seconds' => true,
            'required' => true,
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => '提交'
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars += [
            'message' => [
                'type' => 'warning',
                'content' => '开启后用户将无法正常访问网站首页！'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParameterBag::class
        ));
    }
}
