<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-16 03:12:59
 */

namespace Yeskn\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;
use Yeskn\MainBundle\Entity\Translation;

class LoadTranslationService
{
    private $rootDir;

    private $entityManager;

    public function __construct($rootDir, EntityManager $entityManager)
    {
        $this->rootDir = $rootDir;
        $this->entityManager = $entityManager;
    }

    public function execute()
    {
        $translationRepository = $this->entityManager->getRepository('YesknMainBundle:Translation');

        $translations = $translationRepository->findAll();

        /** @var Translation[] $map */
        $map = [];

        foreach ($translations as $item) {
            $map[$item->getMessageId()] = $item;
        }

        $en = Yaml::parseFile($this->rootDir . '/Resources/translations/messages.en.yml');
        $jp = Yaml::parseFile($this->rootDir . '/Resources/translations/messages.jp.yml');
        $cn = Yaml::parseFile($this->rootDir . '/Resources/translations/messages.zh_CN.yml');
        $tw = Yaml::parseFile($this->rootDir . '/Resources/translations/messages.zh_TW.yml');

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
                    throw new \Exception($method . 'is not a method for Translation');
                }

            }

            $dump = Yaml::dump($message);

            $file = $this->rootDir . '/Resources/translations/messages.'.$fileFix.'.yml';

            file_put_contents($file,$dump);
        }

        return true;
    }
}