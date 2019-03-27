<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-05-27 15:38:34
 */

namespace Yeskn\MainBundle\EventListener;

use Yeskn\MainBundle\Entity\User;
use Yeskn\Support\AbstractControllerListener;

class ControllerListener extends AbstractControllerListener
{
    static $increasedTodayActive = false;

    public function onKernelController()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user && self::$increasedTodayActive == false) {
            self::$increasedTodayActive = true;
            $this->em->getRepository('YesknMainBundle:Active')->increaseTodayActive($user);
        }
    }
}
