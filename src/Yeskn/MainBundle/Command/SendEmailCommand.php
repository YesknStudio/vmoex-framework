<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-13 10:39:19
 */

namespace Yeskn\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    private $email;

    protected function configure()
    {
        $this->setName('send-email');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('no-replay@wpcraft.cn')
            ->setTo('singviy@qq.com')
            ->setBody(
                $this->renderView('emails/verify-email.html.twig', [
                    'code' => 233333
                ]), 'text/html'
            );

        $this->getContainer()->get('mailer')->send($message);

        $output->writeln('finished!');
    }

    private function renderView($view, $param)
    {
        return $this->getContainer()->get('twig')->render($view, $param);
    }

    private function mailer()
    {
        return $this->getContainer()->get(\Swift_Mailer::class);
    }


}
