<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 18:40:37
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Form\DataTransfer\DatetimeToStringTransfer;
use Yeskn\MainBundle\Form\Entity\BasicManage;
use Yeskn\MainBundle\Form\Type\ImageInputType;

class ManageBasicType extends AbstractType
{
    private $webRoot;

    public function __construct($webRoot)
    {
        $this->webRoot = $webRoot;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('siteLogo', ImageInputType::class, [
                'label' => '网站LOGO',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        );
        $builder->add(
            $builder->create('siteSince', DateType::class, [
                'label' => '成立时间',
                'widget' => 'single_text'
            ])
            ->addModelTransformer(new DatetimeToStringTransfer())
        );
        $builder->add('siteVersion', null, ['label' => '网站版本']);

        $builder->add('siteAnnounce', CheckboxType::class, [
            'label' => '开启公告',
            'required' => false,
            'attr' => [
                'help' => '请在翻译管理中编辑词条banner_announce来修改公告内容'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BasicManage::class
        ));
    }
}