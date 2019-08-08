<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Entity;

use TickTackk\EmailListVerifyIntegration\Globals;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * Class User
 * 
 * Extends \XF\Entity\User
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Entity
 */
class User extends XFCP_User
{
    /**
     * @param $email
     *
     * @return bool
     */
    protected function verifyEmail(&$email) : bool
    {
        Globals::$emailValidationReturnsTrue = true;

        try
        {
            $result = parent::verifyEmail($email);

            if ($result && !empty(Globals::$emailValidationError))
            {
                $this->error(
                    \XF::phrase('emailListVerifyIntegration_email_address_you_entered_does_not_exist'),
                    'email'
                );

                return false;
            }

            return $result;
        }
        finally
        {
            Globals::$emailValidationReturnsTrue = null;
            Globals::$emailValidationError = null;
        }
    }
}