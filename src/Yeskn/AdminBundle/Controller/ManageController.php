<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 10:12:29
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Form\Logic\ManageBasicLogic;
use Yeskn\MainBundle\Form\ManageBasicType;
use Yeskn\Support\File\ImageHandler;

/**
 * Class ManageController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/manage")
 */
class ManageController extends Controller
{
    /**
     * @Route("/basic", name="admin_manage_basic")
     *
     * @param $request
     * @param $manageBasicLogic
     * @param $imageHandler
     *
     * @return Response
     */
    public function basicAction(Request $request, ManageBasicLogic $manageBasicLogic, ImageHandler $imageHandler)
    {
        $basic = $manageBasicLogic->getBasicManage();

        $oldLogo = $basic->getSiteLogo();

        if ($oldLogo) {
            $basic->setSiteLogo(new File($oldLogo, false));
        }

        $basicForm = $this->createForm(ManageBasicType::class, $basic);

        $basicForm->handleRequest($request);

        if ($basicForm->isSubmitted() && $basicForm->isValid()) {
            if ($file = $basic->getSiteLogo()) {
                $imageHandler->setSize(340, 115);
                $imageHandler->setFileName('/assets/images/logo.png');
                $imageHandler->handle($basic, 'siteLogo');
            } else {
                $basic->setSiteLogo($oldLogo);
            }

            $manageBasicLogic->setBasicManage($basic);

            $this->addFlash('success', '设置成功');

            return $this->redirectToRoute('admin_manage_basic');
        }

        return $this->render('@YesknAdmin/manage/basic.html.twig', [
            'basicForm' => $basicForm->createView()
        ]);
    }
}