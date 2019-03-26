<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-26 21:09:45
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Yeskn\Support\ParameterBag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionsGirlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('girl_enable', CheckboxType::class, [
            'label' => '启用看板娘',
            'required' => false,
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => '提交'
        ]);
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
