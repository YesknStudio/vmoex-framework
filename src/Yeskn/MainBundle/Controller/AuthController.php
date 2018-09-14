<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 15:26:13
 */

namespace Yeskn\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Yeskn\MainBundle\Entity\User;
use Yeskn\MainBundle\Form\UserType;

class AuthController extends Controller
{
    /**
     * @Route("/login", name="login", methods={"GET"})
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        //get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@YesknMain/Auth/login.html.twig', array(
            // ...
            'last_username' => $lastUsername,
            'error'         => $error
        ));
    }

    /**
     * @Route("/register", name="register")
     * @param $request Request
     * @throws
     * @return RedirectResponse|Response
     */
    public function regAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $em = $this->getDoctrine()->getManager();

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $check = $em->getRepository('YesknMainBundle:User')
                ->checkEmailAndUsername($user->getEmail(), $user->getUsername());
            $user->setSalt(md5(uniqid()));

            if ($check) {
                $this->addFlash('error', '用户名或者邮箱已经注册');
                return $this->redirectToRoute('register');
            }
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRegisterAt(new \DateTime());

            $fileName = md5($user->getUsername()) . '.png';
            $file = $this->container->getParameter('kernel.project_dir') . '/web/avatar/' . $fileName;

            $identicon = new \Identicon\Identicon();
            $avatar = $identicon->getImageData($user->getUsername(), 100);

            file_put_contents($file, $avatar);

            $user->setAvatar('avatar/' . $fileName);
            $user->setNickname($user->getUsername());
            $user->setLoginAt(new \DateTime());
            $user->setRole('ROLE_USER');

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', '注册成功，请使用账号名"' . $user->getUsername().'"登录');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            '@YesknMain/auth/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/login", name="do_login", methods={"POST"})
     */
    public function doLoginAction()
    {
        throw new \Exception('this should never be reached!');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should never be reached!');
    }
}
