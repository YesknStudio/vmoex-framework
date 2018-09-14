<?php
/**
 * This file is part of project JetBlog.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\MainBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Yeskn\MainBundle\Entity\User;

class ControllerListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController()
    {
        $user = $this->getUser();

        if ($user) {
            $activeRepository = $this->container->get('doctrine')
                ->getRepository('YesknMainBundle:Active');
            $activeRepository->increaseTodayActive($user);
        }
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}