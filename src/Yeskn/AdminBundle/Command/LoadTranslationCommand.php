<?php

/**
 * This file is part of project yeskn-studio/wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-14 23:46:25
 */

namespace Yeskn\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Yeskn\MainBundle\Entity\Translation;

class LoadTranslationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('load-translation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $translationRepository = $em->getRepository('YesknMainBundle:Translation');

        $translations = $translationRepository->findAll();

        /** @var Translation[] $map */
        $map = [];

        foreach ($translations as $item) {
            $map[$item->getMessageId()] = $item;
        }

        $en = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.en.yml');
        $jp = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.jp.yml');
        $cn = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.zh_CN.yml');
        $tw = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.zh_TW.yml');

        $messages = [$en, $jp, $cn, $tw];
        $types = ['English', 'Japanese', 'Chinese', 'Taiwanese'];
        $fileFixes = ['en', 'jp', 'zh_CN', 'zh_TW'];

        foreach ($messages as $index => $message) {
            $type = $types[$index];
            $fileFix = $fileFixes[$index];

            foreach ($map as $messageId => $translation) {
                $method = 'get' . $type;
                if (method_exists($translation, $method)) {
                    if (empty($message[$messageId]) || $translation->$method() != $message[$messageId]) {
                        $message[$messageId] = $translation->$method();
                    }
                } else {
                    $output->writeln($method . 'is not a method for Translation');
                    exit(-1);
                }

            }

            $dump = Yaml::dump($message);

            $file = $container->getParameter('kernel.root_dir') . '/Resources/translations/messages.'.$fileFix.'.yml';

            file_put_contents($file,$dump);
        }

        $output->writeln('finished!');
    }
}