<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-28 12:59:50
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Yeskn\Support\AbstractController;

class UsersController extends AbstractController
{
    /**
     * @Route("/users/rank/gold", name="users_rank")
     */
    public function rankGoldAction()
    {
        $users = $this->getRepo('YesknMainBundle:User')
            ->findBy([], ['gold' => 'DESC'], 100);

        return $this->render('@YesknMain/users/rich.html.twig', [
            'users' => $users
        ]);

    }

    /**
     * @Route("/users/rank/checkin", name="users_rank_checkin")
     */
    public function rankSignAction()
    {
        $users = $this->getRepo('YesknMainBundle:User')
            ->findBy([], ['signDay' => 'DESC'], 100);

        return $this->render('@YesknMain/users/sign.html.twig', [
            'users' => $users
        ]);
    }
}
