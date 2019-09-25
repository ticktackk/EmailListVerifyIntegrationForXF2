<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Entity;

use TickTackk\EmailListVerifyIntegration\Globals;

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
        Globals::$useEmailListVerify = true;

        try
        {
            $isVerified = parent::verifyEmail($email);


            /*
             * need to show better and shorter novice understandable error messages
             *
             * if (!$isVerified && Globals::$emailValidationError)
            {
                $this->error(Globals::$emailValidationError, 'email');
            }*/

            return $isVerified;
        }
        finally
        {
            Globals::$useEmailListVerify = null;
        }
    }
}