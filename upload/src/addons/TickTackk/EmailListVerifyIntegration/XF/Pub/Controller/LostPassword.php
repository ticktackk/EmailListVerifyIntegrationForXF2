<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Pub\Controller;

use TickTackk\EmailListVerifyIntegration\Globals;

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
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
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
            Globals::$emailValidationError = null;
        }
    }
}