<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 22:54:35
 */

namespace Yeskn\MainBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Tab;
use Yeskn\MainBundle\Form\Type\ImageInputType;

class TabType extends AbstractType
{
    private $webRoot;

    public function __construct($webRoot)
    {
        $this->webRoot = $webRoot;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Tab $entity */
        $entity = $builder->getData();

        $builder->add('level', ChoiceType::class, [
            'label' => '层级',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                '顶级' => '1',
                '子级' => 2
            ]
        ]);

        $builder->add('name', TextType::class, [
            'label' => '名称'
        ]);

        $builder->add('alias', TextType::class, [
            'label' => '别名'
        ]);

        $builder->add('parent', EntityType::class, [
            'class' => Tab::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('p')
                    ->where('p.level = 1');
            },
            'choice_label' => 'name',
            'choice_value' => 'id',
            'label' => '父级板块'
        ]);

        $builder->add('description', TextareaType::class, ['label' => '描述']);

        $builder->add(
            $builder->create('avatar', ImageInputType::class, [
                'label' => '板块标志',
                'required' => $entity->getAvatar() ? false : true
            ])
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tab::class,
        ]);
    }
}