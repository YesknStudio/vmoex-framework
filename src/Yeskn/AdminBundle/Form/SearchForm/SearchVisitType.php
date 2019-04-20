<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-20 18:19:44
 */

namespace Yeskn\AdminBundle\Form\SearchForm;

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Yeskn\MainBundle\Form\Type\DatetimeRangeType;

class SearchVisitType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('ip', SearchType::class, [
            'label' => 'IP',
            'required' => false
        ]);

        $builder->add('createdAt', DatetimeRangeType::class, [
            'label' => '访问时间',
            'field_type' => 'datetime',
            'required' => false,
        ]);
    }



}
