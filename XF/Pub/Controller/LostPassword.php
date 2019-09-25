<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Pub\Controller;

use TickTackk\EmailListVerifyIntegration\Globals;
use XF\Mvc\Reply\Error as ErrorReply;
use XF\Mvc\Reply\Redirect as RedirectReply;
use XF\Mvc\Reply\View as ViewReply;

/**
 * Class LostPassword
 * 
 * Extends \XF\Pub\Controller\LostPassword
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Pub\Controller
 */
class LostPassword extends XFCP_LostPassword
{
    /**
     * @return ErrorReply|RedirectReply|ViewReply
     */
    public function actionIndex()
    {
        Globals::$useEmailListVerify = $this->options()->emailListVerifyIntegrationEnableFor['password_reset'];

        try
        {
            return parent::actionIndex();
        }
        finally
        {
            Globals::$useEmailListVerify = null;
        }
    }
}