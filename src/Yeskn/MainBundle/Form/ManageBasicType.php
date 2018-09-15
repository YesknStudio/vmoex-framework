<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 18:40:37
 */

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Form\DataTransfer\DatetimeToStringTransfer;
use Yeskn\MainBundle\Form\DataTransfer\StringToImageTransformer;
use Yeskn\MainBundle\Form\Entity\BasicManage;

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
            $builder->create('siteLogo', FileType::class, [
                'label' => '网站LOGO',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->addModelTransformer(new StringToImageTransformer($this->webRoot))
        );
        $builder->add(
            $builder->create('siteSince', DateType::class, [
                'label' => '成立时间',
                'widget' => 'single_text'
            ])
            ->addModelTransformer(new DatetimeToStringTransfer())
        );
        $builder->add('siteVersion', null, ['label' => '网站版本']);
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