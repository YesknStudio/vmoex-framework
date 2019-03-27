<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-27 23:41:54
 */

namespace Yeskn\MainBundle\Services;

use Yeskn\MainBundle\Entity\User;

class RandomAvatarService
{
    private $avaDir;

    public function __construct($projectDir)
    {
        $this->avaDir = $projectDir . '/web/avatar';
    }

    public function handle(User $user)
    {
        $fileName = md5($user->getUsername() . time()) . '.png';
        $file = $this->avaDir . '/' . $fileName;

        // 使用随机二次元头像
        $i = mt_rand(1, 99999);

        file_put_contents($file,
            file_get_contents("https://www.thiswaifudoesnotexist.net/example-{$i}.jpg")
        );

        $user->setAvatar('avatar/' . $fileName);
    }
}
