<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Pub\Controller;

use TickTackk\EmailListVerifyIntegration\Globals;

/**
 * Class Register
 * 
 * Extends \XF\Pub\Controller\Register
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Pub\Controller
 */
class Register extends XFCP_Register
{
    /**
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     */
    public function actionIndex()
    {
        Globals::$useEmailListVerify = $this->options()->emailListVerifyIntegrationEnableFor['account_registration'];

        try
        {
            return parent::actionIndex();
        }
        finally
        {
            Globals::$useEmailListVerify = null;
            Globals::$emailValidationError = null;
        }
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
     */
    public function actionRegister()
    {
        Globals::$useEmailListVerify = $this->options()->emailListVerifyIntegrationEnableFor['account_registration'];

        try
        {
            return parent::actionRegister();
        }
        finally
        {
            Globals::$useEmailListVerify = null;
            Globals::$emailValidationError = null;
        }
    }
}