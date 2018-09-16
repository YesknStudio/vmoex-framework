<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 10:12:29
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Form\Logic\ManageBasicLogic;
use Yeskn\MainBundle\Form\ManageBasicType;
use Intervention\Image\ImageManagerStatic as Image;

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
     *
     * @return Response
     */
    public function basicAction(Request $request, ManageBasicLogic $manageBasicLogic)
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
                $extension = $file->guessExtension();
                $webRoot = $this->getParameter('kernel.project_dir') . '/web';
                $fileName = 'upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;

                $targetPath = $webRoot .  '/' . $fileName;

                $fs = new Filesystem();
                $fs->copy($file->getRealPath(), $targetPath);

                Image::configure(array('driver' => 'gd'));

                $image = Image::make($targetPath);
                $image->resize(100, 100)->save();

                $basic->setSiteLogo($fileName);
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