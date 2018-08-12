<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\BlogBundle\EventListener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Yeskn\BlogBundle\Entity\User;

class ControllerListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $user = $this->getUser();

        if ($user) {
            $activeRepository = $this->container->get('doctrine')
                ->getRepository('YesknBlogBundle:Active');
            $activeRepository->increaseTodayActive($user);
        }
    }

    /**
     * @return User|void
     */
    protected function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}