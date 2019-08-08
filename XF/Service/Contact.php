<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Service;

use TickTackk\EmailListVerifyIntegration\Globals;

/**
 * Class Contact
 * 
 * Extends \XF\Service\Contact
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Service
 */
class Contact extends XFCP_Contact
{
    /**
     * @param string $email
     * @param null $error
     *
     * @return bool
     */
    protected function validateEmail(&$email, &$error = null)
    {
        Globals::$useEmailListVerify = $this->app->options()->emailListVerifyIntegrationEnableFor['contact_us'];

        try
        {
            return parent::validateEmail($email, $error);
        }
        finally
        {
            Globals::$useEmailListVerify = null;
            Globals::$emailValidationError = null;
        }
    }
}