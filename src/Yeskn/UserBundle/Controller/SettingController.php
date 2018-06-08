<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 2016/6/23
 * Time: 2:10
 */

namespace Yeskn\UserBundle\Controller;

use Intervention\Image\ImageManagerStatic as Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Yeskn\BlogBundle\Entity\User;

/**
 * Class SettingController
 * @Route("/setting")
 * @package Yeskn\UserBundle\Controller
 */
class SettingController extends Controller
{
    /**
     * @Route("/", name="user_setting")
     */
    public function index()
    {
        return $this->render('@YesknUser/Default/setting.html.twig');
    }

    /**
     * @Route("/modify_info", name="modify_user_info")
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

            $avatarPath = $request->getSchemeAndHttpHost() . '/avatar/' . $fileName;

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