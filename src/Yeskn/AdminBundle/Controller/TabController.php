<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 09:38:58
 */

namespace Yeskn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Tab;
use Yeskn\MainBundle\Form\TabType;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class TabController
 * @package Yeskn\AdminBundle\Controller
 *
 * @Route("/tab")
 */
class TabController extends Controller
{
    /**
     * @Route("/", name="admin_tab_index")
     */
    public function indexAction()
    {
        /** @var Tab[] $list */
        $list = $this->getDoctrine()->getRepository('YesknMainBundle:Tab')->findAll();


        $form = $this->createForm(TabType::class, new Tab());

        return $this->render('@YesknAdmin/tab/index.html.twig', [
            'list' => $list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="admin_tab_add", methods={"POST"})
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $tab = new Tab();

        $form = $this->createForm(TabType::class, $tab);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $file = $tab->getAvatar();
            $extension = $file->guessExtension();
            $webRoot = $this->getParameter('kernel.project_dir') . '/web';
            $fileName = 'upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;

            $targetPath = $webRoot .  '/' . $fileName;

            $fs = new Filesystem();
            $fs->copy($file->getRealPath(), $targetPath);

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(100, 100)->save();

            $tab->setAvatar($fileName);

            $em->persist($tab);
            $em->flush();

            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return new JsonResponse(['ret' => 0, 'msg' => $form->getErrors()->current()->getMessage()]);
    }

    /**
     * @Route("/edit_{id}", name="admin_tab_edit")
     *
     * @param $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository('YesknMainBundle:Tab');

        $tab = $repo->find($id);

        $oldAvatar = $tab->getAvatar();

        $tab->setAvatar(new File($oldAvatar, false));

        $form = $this->createForm(TabType::class, $tab);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            /** @var UploadedFile $file */
            if ($file = $tab->getAvatar()) {
                $extension = $file->guessExtension();
                $webRoot = $this->getParameter('kernel.project_dir') . '/web';
                $fileName = 'upload/' . time() . mt_rand(1000, 9999) . '.' . $extension;

                $targetPath = $webRoot .  '/' . $fileName;

                $fs = new Filesystem();
                $fs->copy($file->getRealPath(), $targetPath);

                Image::configure(array('driver' => 'gd'));

                $image = Image::make($targetPath);
                $image->resize(100, 100)->save();

                $tab->setAvatar($fileName);
            } else {
                $tab->setAvatar($oldAvatar);
            }

            $em->persist($tab);
            $em->flush();

            $this->addFlash('success', '操作成功');

            return new JsonResponse(['status' => 1, 'message' => '操作成功']);
        }

        return $this->render('@YesknAdmin/modals/entity-modal.html.twig', [
            'form' => $form->createView(),
            'title' => '编辑板块',
            'action' => $this->generateUrl('admin_tab_edit', ['id' => $id]),
            'formId' => $request->get('r')
        ]);
    }
}