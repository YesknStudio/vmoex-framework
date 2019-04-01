<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-01 14:44:00
 */

namespace Yeskn\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Form\DataTransfer\DatetimeRangeToStringTransformer;
use Yeskn\MainBundle\Form\DataTransfer\DatetimeToStringTransfer;

/**
 * 时间范围选择器
 *
 * Class DatetimeRangeType
 * @package Yeskn\MainBundle\Form\Type
 */
class DatetimeRangeType extends AbstractType implements FormTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'field_type' => 'datetime',
            'date_range' => true
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($builder->getOption('date_range')) {
            $builder->addViewTransformer(new DatetimeRangeToStringTransformer());
        } else {
            $builder->addViewTransformer(new DatetimeToStringTransfer(
                $builder->getOption('field_type') === 'datetime'
                )
            );
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars += $options;
    }


    public function getParent()
    {
        return TextType::class;
    }
}
