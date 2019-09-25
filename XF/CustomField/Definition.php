<?php

namespace TickTackk\EmailListVerifyIntegration\XF\CustomField;

use TickTackk\EmailListVerifyIntegration\Globals;
use XF;

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
        Globals::$useEmailListVerify = XF::options()->emailListVerifyIntegrationEnableFor['custom_field'];

        try
        {
            return parent::_validateMatchTypeEmail($value, $error, $existingValue);
        }
        finally
        {
            Globals::$useEmailListVerify = null;
        }
    }
}