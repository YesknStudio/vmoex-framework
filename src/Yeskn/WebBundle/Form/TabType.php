<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\WebBundle\Entity\Tab;
use Yeskn\WebBundle\Form\DataTransformer\StringToImageTransformer;

class TabType extends AbstractType
{
    private $transformer;
    private $container;

    public function __construct(ContainerInterface $container, StringToImageTransformer $transformer)
    {
        $this->container = $container;
        $transformer->setPathPrefix('tavatar/');
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,array('label' => '板块名'))
            ->add('alias',TextType::class,array('label' => '别名'))
            ->add('level',ChoiceType::class,array(
                'label' => '层级',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    '顶级' => '1',
                    '子级' => 2
                ]
            ))
            ->add('parent', EntityType::class, [
                'class' => Tab::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('p')
                        ->where('p.level = 1');
                },
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => '父级板块'
            ])
            ->add('description', TextareaType::class, ['label' => '描述'])
            ->add('avatar', FileType::class, [
                'label' => '板块标志',
                'required' => false
            ])
            ->add('submit',SubmitType::class,array('label' => '提交'));

        $builder->get('avatar')->addModelTransformer($this->transformer);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Tab::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'yeskn_blogbundle_tab';
    }


}
