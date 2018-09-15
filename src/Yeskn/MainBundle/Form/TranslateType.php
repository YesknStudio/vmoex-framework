<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 14:59:07
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TranslateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('messageId', null, ['label' => '词条Id']);
        $builder->add('chinese', null, ['label' => '中文']);
        $builder->add('english', null, ['label' => '英文']);
        $builder->add('japanese', null, ['label' => '日文']);
        $builder->add('taiwanese', null, ['label' => '中文繁体']);
    }
}