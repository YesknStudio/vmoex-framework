<?php
/**
 * This file is part of project Vmoex.
 *
 * Author: Jake
 * Create: 2018-05-27 17:11:43
 */

namespace Yeskn\WebBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Yeskn\WebBundle\Entity\Sign;
use Yeskn\WebBundle\Entity\User;

class SignController extends Controller
{
    /**
     * @Route("/sign", methods={"POST"}, name="sign")
     */
    public function sign()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $signRepository = $this->getDoctrine()->getRepository('YesknWebBundle:Sign');
        $get = $signRepository->findOneBy(['user' => $user, 'date' => new \DateTime()]);

        $getBefore = $signRepository->findOneBy(['user' => $user, 'date' => new \DateTime('yesterday')]);

        if ($get) {
            return new JsonResponse(['ret' => 0, '你今天已经签过到！']);
        }

        $sign = new Sign();

        $sign->setDate(new \DateTime());
        $sign->setUser($user);

        $randomGold = mt_rand(3, 10);

        $sign->setGotGold($randomGold);

        $em = $this->getDoctrine()->getManager();

        $em->persist($sign);

        $user->setGold($user->getGold() + $randomGold);

        if ($getBefore) {
            $user->setSignDay($user->getSignDay()+1);
        } else {
            $user->setSignDay(1);
        }

        $em->flush();
        return new JsonResponse([
            'ret' => 1,
            'msg' => '本次签到获得了'. $randomGold . '个金币！'
        ]);
    }
}