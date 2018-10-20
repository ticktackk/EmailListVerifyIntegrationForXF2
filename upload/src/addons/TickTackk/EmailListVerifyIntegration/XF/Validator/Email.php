<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Validator;

use TickTackk\EmailListVerifyIntegration\Globals;

/**
 * Class Email
 * 
 * Extends \XF\Validator\Email
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Validator
 */
class Email extends XFCP_Email
{
    /**
     * @param string $value
     * @param null $errorKey
     *
     * @return bool
     */
    public function isValid($value, &$errorKey = null) : bool
    {
        $isValid = parent::isValid($value, $errorKey);

        if ($isValid && Globals::$useEmailListVerify && !$this->options['allow_empty'])
        {
            /** @var \TickTackk\EmailListVerifyIntegration\Service\EmailListVerify\Verifier $verifier */
            $verifier = $this->app->service('TickTackk\EmailListVerifyIntegration:EmailListVerify\Verifier', $value);
            if (!$verifier->verify($error))
            {
                Globals::$emailValidationError = $error;
                return Globals::$emailValidationReturnsTrue;
            }
        }

        return $isValid;
    }
}