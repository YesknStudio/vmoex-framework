<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-25 01:44:20
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Yeskn\Support\AbstractController;

class AnnounceController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \UnexpectedValueException
     *
     * @Route("/announce/history", name="announce_history")
     */
    public function listAction()
    {
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Announce')
            ->findBy([], [
                'show' => 'DESC',
                'id' => 'DESc'
            ]);

        return $this->render('@YesknMain/announce/list.html.twig', [
            'list' => $list
        ]);
    }
}
