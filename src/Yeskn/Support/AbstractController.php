<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-13 10:14:24
 */

namespace Yeskn\Support;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Yeskn\MainBundle\Entity\User;
use Yeskn\Support\Http\ApiFail;

class AbstractController extends Controller
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \LogicException
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $repo
     * @return \Doctrine\Common\Persistence\ObjectRepository
     * @throws \LogicException
     */
    public function getRepo($repo)
    {
        return $this->getDoctrine()->getRepository($repo);
    }

    /**
     * @return UserInterface|User
     * @throws \LogicException
     */
    public function getUser()
    {
        /** @var User|UserInterface $user */
        $user = parent::getUser();

        return $user;
    }

    /**
     * @param $id
     * @return string
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function trans($id)
    {
        return $this->get('translator')->trans($id);
    }

    /**
     * @param $msg
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|ApiFail
     * @throws \LogicException
     */
    public function errorResponse($msg)
    {
        $isXhr = $this->get('request_stack')->getCurrentRequest()->isXmlHttpRequest();

        if ($isXhr) {
            return new ApiFail($msg);
        }

        $this->addFlash('danger', $msg);

        /** @var Request $request */
        $request = $this->get('request_stack')->getCurrentRequest();

        return $this->redirect($request->headers->get('referer'));
    }
}
