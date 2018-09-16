<?php
namespace Yeskn\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => '用户名', 'translation_domain' => 'messages'])
            ->add('email', EmailType::class, ['label' => '邮箱', 'translation_domain' => 'messages'])
            ->add('password', PasswordType::class,['label' => '密码', 'translation_domain' => 'messages'])
            ->add('submit', SubmitType::class, ['label' => 'register', 'translation_domain' => 'messages'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Yeskn\MainBundle\Entity\User',
        ]);
    }
}