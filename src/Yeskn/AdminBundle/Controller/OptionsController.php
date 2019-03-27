<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
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
use Yeskn\MainBundle\Form\Logic\OptionsLogic;
use Yeskn\MainBundle\Form\OptionsBasic;
use Yeskn\Support\File\ImageHandler;

/**
 * Class ManageController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/options")
 */
class OptionsController extends Controller
{
    /**
     * @Route("/basic", name="admin_manage_basic")
     *
     * @param $request
     * @param $optionsLogic
     * @param $imageHandler
     *
     * @return Response
     */
    public function basicAction(Request $request, OptionsLogic $optionsLogic, ImageHandler $imageHandler)
    {
        $basic = $optionsLogic->getBasicOptions();

        $oldLogo = $basic->get('siteLogo');

        if ($oldLogo) {
            $basic->set('siteLogo', new File($oldLogo, false));
        }

        $basicForm = $this->createForm(OptionsBasic::class, $basic);

        $basicForm->handleRequest($request);

        if ($basicForm->isSubmitted() && $basicForm->isValid()) {
            if ($file = $basic->get('siteLogo')) {
                $imageHandler->setSize(340, 115);
                $imageHandler->setFileName('/assets/images/logo.png');
                $imageHandler->handle($basic, 'siteLogo');
            } else {
                $basic->set('siteLogo', $oldLogo);
            }

            $optionsLogic->setOptions($basic->all());

            $this->addFlash('success', '设置成功');

            return $this->redirectToRoute('admin_manage_basic');
        }

        return $this->render('@YesknAdmin/manage/basic.html.twig', [
            'basicForm' => $basicForm->createView()
        ]);
    }

    /**
     * @Route("/general/{optionGroup}", name="admin_options_general")
     *
     * @param Request $request
     * @param $optionGroup
     * @param $optionsLogic
     * @return Response
     */
    public function generalAction(Request $request, $optionGroup, OptionsLogic $optionsLogic)
    {
        $options = $optionsLogic->getOptions(
            $optionsLogic->getGroupOptionKeys($optionGroup)
        );

        $form = $this->createForm(
            $optionsLogic->getGroupFormType($optionGroup),
            $options
        );

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $optionsLogic->setOptions($options->all(), $optionGroup);

            $this->addFlash('success', '设置成功');

            return $this->redirectToRoute('admin_options_general', [
                'optionGroup' => $optionGroup
            ]);
        }

        return $this->render('@YesknAdmin/manage/general.html.twig', [
            'groupName' => $optionsLogic->getGroupName($optionGroup),
            'form' => $form->createView()
        ]);
    }
}
