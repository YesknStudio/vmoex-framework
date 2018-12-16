<?php

/**
 * This file is part of project project yeskn-studio/vmoex-framework.
 *
 * Author: Jaggle
 * Create: 2018-09-14 22:17:58
 */

namespace Yeskn\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Yeskn\MainBundle\Entity\Translation;

class TranslationPersistCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('translation:persist');
        $this->setDescription('persist translation from file to db');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $en = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.en.yml');
        $jp = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.jp.yml');
        $cn = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.zh_CN.yml');
        $tw = Yaml::parseFile($container->getParameter('kernel.root_dir') . '/Resources/translations/messages.zh_TW.yml');

        $translationRepository = $container->get('doctrine')->getRepository('YesknMainBundle:Translation');
        $em = $container->get('doctrine.orm.entity_manager');

        $messages = [$cn, $en, $jp, $tw];
        $types = ['Chinese', 'English', 'Japanese', 'Taiwanese'];

        foreach ($messages as $typeIndex => $message) {
            $type = $types[$typeIndex];

            foreach ($message as $key => $value) {
                $one = $translationRepository->findOneBy(['messageId' => $key]);
                if (empty($one)) {
                    $one = new Translation();
                    $em->persist($one);
                }

                $one->setMessageId($key);

                $method = 'set' . $type;

                if (method_exists($one, $method)) {
                    $one->$method($value);
                } else {
                    $output->writeln($method . 'is not a method for Translation');
                    exit(-1);
                }

                $em->flush();
            }
        }

        $output->writeln('finished!');
    }
}
