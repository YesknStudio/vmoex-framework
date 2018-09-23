<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-22 17:26:53
 */

namespace Yeskn\MainBundle\Form\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "user_setting_wrong_current_password"
     * )
     */
    public $oldPassword;

    /**
     * @Assert\Length(
     *     min = 6,
     *     max="18"
     * )
     */
    public $newPassword;
}