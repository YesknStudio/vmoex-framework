<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-30 13:10:11
 */

namespace Yeskn\AdminBundle\Form\SearchForm;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchPostType extends DefaultSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm( $builder, $options);

        $builder->add('title', TextType::class, [
            'label' => '标题',
            'required' => false
        ]);
        $builder->add('author', TextType::class, [
            'label' => '作者',
            'required' => false
        ]);
        $builder->add(
            'createdAt', DateType::class, [
            'label' => '发布时间',
            'input' => 'string',
            'widget' => 'single_text',
            'required' => false,
        ]        );
        $builder->add('status', ChoiceType::class, [
            'label' => '状态',
            'required' => false,
            'choices' => [
                '所有' => '',
                '已发布' => 'published',
                '草稿' => 'draft'
            ]
        ]);
    }

}
