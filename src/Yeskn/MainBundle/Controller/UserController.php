<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:24:09
 */

namespace Yeskn\MainBundle\Controller;

use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\User;

/**
 * Class UserController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/home", name="user_home")
     */
    public function homeAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        $user = $this->getDoctrine()->getRepository('YesknMainBundle:User')
            ->findOneBy(['username' => $user->getUsername()]);
        if (!$user) {
            return $this->render('@YesknMain/error.html.twig', [
                'message' => '用户不存在'
            ]);
        }

        $userActive = $this->getDoctrine()->getRepository('YesknMainBundle:Active')
            ->findOneBy(['user' => $user], ['id' => 'DESC']);

        $online = false;

        /** @var \DateTime $updatedAt */
        $updatedAt = $userActive->getUpdatedAt();

        if ($userActive and $updatedAt->getTimestamp() >= time() - 15*60) {
            $online = true;
        }

        return $this->render('@YesknMain/user/user-home.html.twig', [
            'user' => $user,
            'online' => $online,
            'userActive' => $userActive
        ]);
    }

    /**
     * @Route("/setting", name="user_setting")
     */
    public function settingAction()
    {
        return $this->render('@YesknMain/user/setting.html.twig');
    }

    /**
     * @Route("/setting/modify", name="modify_user_info")
     */
    public function modifyUserInfo(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['ret' => 0, 'msg' => '用户未登录']);
        }

        /**
         * @var UploadedFile $avatar
         */
        $avatar = $request->files->get('avatar');

        if ($avatar) {
            $path = $avatar->getRealPath();

            if (filesize($path) > 2*1024*1024) {
                @unlink($path);
                return new JsonResponse(['ret' => 0, 'msg' => 'data too long']);
            }

            $ext = $avatar->getClientOriginalExtension();
            $ext = strtolower($ext);

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                @unlink($path);
                return new JsonResponse(['ret' => 0, 'msg' => 'file not support']);
            }

            $fs = new Filesystem();

            $fileName = md5($user->getUsername()) . '.'.$ext;

            $targetPath = $this->container->getParameter('kernel.project_dir') . '/web/avatar/' . $fileName;
            $fs->copy($path, $targetPath);

            $avatarPath = 'avatar/' . $fileName;

            Image::configure(array('driver' => 'gd'));

            $image = Image::make($targetPath);
            $image->resize(100, 100)->save();
        }

        $user->setNickname($request->get('nickname'));
        $user->setRemark($request->get('remark'));

        if (!empty($avatarPath)) {
            $user->setAvatar($avatarPath);
        }

        $em = $this->getDoctrine()->getManager();

        $em->flush();

        return $this->redirectToRoute('user_setting');
    }

}