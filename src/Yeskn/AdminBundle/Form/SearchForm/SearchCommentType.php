<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-13 09:00:34
 */

namespace Yeskn\AdminBundle\Form\SearchForm;

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Yeskn\MainBundle\Form\Type\DatetimeRangeType;

class SearchCommentType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm( $builder, $options);

        $builder->add('content', SearchType::class, [
            'label' => '内容',
            'required' => false
        ]);

        $builder->add('title', SearchType::class, [
            'label' => '文章标题',
            'required' => false
        ]);

        $builder->add('createdBy', SearchType::class, [
            'label' =>'作者',
            'required' => false
        ]);

        $builder->add('createdAt', DatetimeRangeType::class, [
            'label' => '发布时间',
            'field_type' => 'date',
            'date_range' => true,
            'required' => false,
        ]);
    }
}
