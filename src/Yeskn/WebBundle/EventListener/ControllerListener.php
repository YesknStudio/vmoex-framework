<?php

/*
 * This file is part of project yeskn/vmoex.
 *
 * (c) Jaggle <jaggle@yeskn.com>
 *
 * created at 2018-05-27 15:38:34
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yeskn\WebBundle\EventListener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Yeskn\WebBundle\Entity\User;

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
                ->getRepository('YesknWebBundle:Active');
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