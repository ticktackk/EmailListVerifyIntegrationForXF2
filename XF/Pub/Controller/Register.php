<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Pub\Controller;

use TickTackk\EmailListVerifyIntegration\Globals;
use XF\Mvc\Reply\View as ViewReply;
use XF\Mvc\Reply\Redirect as RedirectReply;
use XF\Mvc\Reply\Error as ErrorReply;

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
     * @return RedirectReply|ViewReply
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
        }
    }

    /**
     * @return ErrorReply|RedirectReply
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
        }
    }
}