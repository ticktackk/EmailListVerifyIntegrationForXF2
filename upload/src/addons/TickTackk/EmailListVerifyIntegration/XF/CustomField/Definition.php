<?php

namespace TickTackk\EmailListVerifyIntegration\XF\CustomField;

use TickTackk\EmailListVerifyIntegration\Globals;

/**
 * Class Definition
 * 
 * Extends \XF\CustomField\Definition
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\CustomField
 */
class Definition extends XFCP_Definition
{
    /**
     * @param $value
     * @param $error
     * @param $existingValue
     *
     * @return mixed
     */
    protected function _validateMatchTypeEmail(&$value, &$error, $existingValue) : bool
    {
        Globals::$emailValidationReturnsTrue = true;
        Globals::$useEmailListVerify = \XF::options()->emailListVerifyIntegrationEnableFor['custom_field'];

        try
        {
            $isValid = parent::_validateMatchTypeEmail($value, $error, $existingValue);

            if (Globals::$emailValidationError)
            {
                $error = 'emailListVerifyIntegration_email_address_you_entered_does_not_exist';
                return false;
            }

            return $isValid;
        }
        finally
        {
            Globals::$emailValidationReturnsTrue = null;
            Globals::$useEmailListVerify = null;
            Globals::$emailValidationError = null;
        }
    }
}