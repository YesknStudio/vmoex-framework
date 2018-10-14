<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:24:09
 */

namespace Yeskn\MainBundle\Controller;

use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\Message;
use Yeskn\MainBundle\Entity\Notice;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Form\ChangePasswordType;
use Yeskn\MainBundle\Form\Entity\ChangePassword;
use Yeskn\Support\AbstractController;
use Yeskn\Support\Http\ApiFail;
use Yeskn\Support\Http\ApiOk;
use Yeskn\Support\Http\HttpResponse;
use Yeskn\Support\Validator;

/**
 * 个人中心
 *
 * Class UserController
 * @package Yeskn\MainBundle\Controller
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    use HttpResponse;

    private function getUserHomeInfo()
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

        return [
            'user' => $user,
            'online' => $online,
            'userActive' => $userActive
        ];
    }

    /**
     * @Route("/home", name="user_home")
     */
    public function homeAction()
    {
        return $this->render('@YesknMain/user/user-home.html.twig',
            $this->getUserHomeInfo() + [
                'scope' => 'home'
            ]);
    }

    /**
     * @Route("/setting", name="user_setting")
     */
    public function settingAction(Request $request)
    {
        $form = $request->get('form');

        if (empty($form)) {
            $form = $this->createForm(ChangePasswordType::class, new ChangePassword());
        }

        return $this->render('@YesknMain/user/setting.html.twig',
            $this->getUserHomeInfo() + [
                'scope' => 'setting',
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/notice", name="user_notice")
     */
    public function noticeAction()
    {
        /** @var Notice[] $unreadNotices */
        $unreadNotices = $this->getDoctrine()->getRepository('YesknMainBundle:Notice')
            ->findBy(['pushTo' => $this->getUser(), 'isRead' => false], ['createdAt' => 'DESC']);

        /** @var Notice[] $readNotices */
        $readNotices = $this->getDoctrine()->getRepository('YesknMainBundle:Notice')
            ->findBy(['pushTo' => $this->getUser(), 'isRead' => true], ['createdAt' => 'DESC']);

        return $this->render('@YesknMain/user/notices.html.twig',
            $this->getUserHomeInfo() + [
                'scope' => 'notice',
                'readNotices' => $readNotices,
                'unreadNotices' => $unreadNotices
            ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     *
     * @Route("/message", name="user_message")
     */
    public function messageAction()
    {
        $messageRepository = $this->getDoctrine()->getRepository('YesknMainBundle:Message');

        /** @var Message[] $rMessages */
        $rMessages = $messageRepository->findBy(['receiver' => $this->getUser()], ['createdAt' => 'DESC']);
        /** @var Message[] $sMessages */
        $sMessages = $messageRepository->findBy(['sender' => $this->getUser()],['createdAt' => 'DESC']);

        return $this->render('@YesknMain/user/messages.html.twig',
            $this->getUserHomeInfo() + [
                'scope' => 'message',
                'rMessages' => $rMessages,
                'sMessages' => $sMessages
            ]);
    }

    /**
     * @Route("/blog", name="user_blog")
     */
    public function blogAction()
    {
        $blogRepo = $this->getDoctrine()->getRepository('YesknMainBundle:Blog');

        $blogList = $blogRepo->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('@YesknMain/user/blog.html.twig',
            $this->getUserHomeInfo() + [
                'scope' => 'blog',
                'blog_list' => $blogList
            ]);
    }

    /**
     * @Route("/change-password", name="user_change_password")
     */
    public function changePasswordAction(Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $user = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $changePasswordModel->newPassword);
            $user->setPassword($password);

            $this->get('doctrine.orm.entity_manager')->flush();

            return $this->redirectToRoute('user_setting');
        }

        return $this->forward('YesknMainBundle:User:setting', [], [
            'form' => $form
        ]);
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

            $fileName = md5($user->getUsername()) . time() . '.'.$ext;

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

    /**
     * @Route("/setting/send-email-code", name="user_setting_send_email_code")
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return ApiFail|ApiOk
     * @throws \LogicException
     */
    public function sendVerifyEmailCodeAction(Request $request,  \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        $email = $request->get('email');

        if ($user->isEmailVerified()) {
            return new ApiFail('邮箱已经验证');
        }

        if (!Validator::isEmail($email)) {
            return new ApiFail('邮箱格式错误');
        }

        $userRepo = $this->getDoctrine()->getRepository('YesknMainBundle:User');

        $findOne = $userRepo->findOneBy(['email' => $email]);

        if ($findOne && $findOne->getId() != $user->getId()) {
            return new ApiFail('邮箱已经注册');
        }

        $code = mt_rand(10000, 99999);

        $request->getSession()->set($user->getUsername() . '_verify_email_code', $code);

        $message = (new \Swift_Message('验证Wpcraft'))
            ->setFrom('no-replay@wpcraft.cn')
            ->setTo($email)
            ->setBody(
                $this->renderView('emails/verify-email.html.twig', [
                    'code' => $code
                ]), 'text/html'
            );

        $mailer->send($message);

        return new ApiOk();
    }

    /**
     * @Route("/setting/verify-email", name="user_setting_verify_email")
     *
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function verifyEmailAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->isEmailVerified()) {
            return $this->errorResponse('邮箱已经验证');
        }

        $email = $request->get('email');

        if (!Validator::isEmail($email)) {
            return $this->errorResponse('邮箱格式错误');
        }

        $code = $request->get('code');

        $sessionCode = $request->getSession()->get($user->getUsername() . '_verify_email_code');


        if (empty($sessionCode) || $sessionCode != $code) {
            return $this->errorResponse('验证码错误');
        }

        $user->setEmail($email);
        $user->setIsEmailVerified(true);

        $this->getEm()->flush();

        $this->addFlash('success' ,'邮箱验证成功！');

        return $this->redirectToRoute('user_setting');
    }
}
