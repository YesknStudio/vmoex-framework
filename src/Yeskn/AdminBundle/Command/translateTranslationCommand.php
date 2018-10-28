<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-10-28 10:15:27
 */

namespace Yeskn\AdminBundle\Command;

use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yeskn\MainBundle\Entity\Translation;
use Yeskn\Support\Command\AbstractCommand;

class translateTranslationCommand extends AbstractCommand
{
    /**
     * @var Client
     */
    private $client;
    private $baiduId;
    private $baiduKey;

    protected function configure()
    {
        $this->setName('translation:trans');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->client = new Client();

        /** @var EntityRepository $transRepo */
        $transRepo = $this->doctrine()->getRepository('YesknMainBundle:Translation');

        /** @var Translation[] $trans */
        $trans = $transRepo->findAll();

        $this->baiduId = $this->parameter('baidu_trans.appid');
        $this->baiduKey = $this->parameter('baidu_trans.key');

        foreach ($trans as $tran) {
            if (empty($tran->getChinese())) {
                continue;
            }

            if (strlen($tran->getEnglish()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'en');
                $tran->setEnglish($ret);
            }

            if (strlen($tran->getJapanese()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'jp');
                $tran->setJapanese($ret);
            }

            if (strlen($tran->getTaiwanese()) === 0) {
                $ret = $this->translate($tran->getChinese(), 'zh', 'cht');
                $tran->setJapanese($ret);
            }

            $this->em()->flush();
        }

        $this->io()->success('finished!');
    }

    public function translate($q, $from, $to)
    {
        $salt = mt_rand(100000, 999999);
        $encode = urlencode($q);

        $response = $this->client->post('https://fanyi-api.baidu.com/api/trans/vip/translate', [
            'form_params' => [
                'q' => $encode,
                'from' => $from,
                'to' => $to,
                'appid' => $this->baiduId,
                'salt' => $salt,
                'sign' => md5($this->baiduId . $q . $salt . $this->baiduKey)
            ]
        ]);

        $result = $response->getBody()->getContents();
        $result = json_decode($result);

        if (property_exists($result, 'dst') && strlen($result->dst) > 0) {
            return $result->dst;
        } else {
            throw new \Exception('translate token ' . $q . ' error: ' . json_encode($result));
        }
    }
}
