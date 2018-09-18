<?php

namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yeskn\MainBundle\Entity\Photo;
use Yeskn\MainBundle\Form\Type\ImageInputType;

class PhotoType extends AbstractType
{
    public function  buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Photo $entity */
        $entity = $builder->getData();

        $builder
            ->add('name',null, array('attr' => array('autofocus' => true), 'label' => '标题',))
            ->add('file',ImageInputType::class, [
               'label' => '封面图',
               'required' => $entity->getFile() ? false : true
           ]);
       ;
    }

    /**
     * @inheritdoc
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array(
           'data_class' => Photo::class,
       ));
    }
}