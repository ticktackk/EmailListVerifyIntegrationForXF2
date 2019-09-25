<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Validator;

use TickTackk\EmailListVerifyIntegration\Globals;
use TickTackk\EmailListVerifyIntegration\Service\EmailListVerify\Verifier as EmailListVerifierSvc;
use XF\Phrase;
use XF\PrintableException;

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
     * @param $value
     * @param null $errorKey
     *
     * @return bool|null
     * @throws PrintableException
     */
    public function isValid($value, &$errorKey = null)
    {
        $isValid = parent::isValid($value, $errorKey);

        if ($isValid && Globals::$useEmailListVerify && !$this->options['allow_empty'])
        {
            /** @var EmailListVerifierSvc $verifier */
            $verifier = $this->app->service('TickTackk\EmailListVerifyIntegration:EmailListVerify\Verifier', $value);
            if (!$verifier->verify($error))
            {
                if ($error instanceof Phrase)
                {
                    $errorKey = $error->getName();
                }
                else
                {
                    $errorKey = 'tckEmailListVerifyIntegration_response.unknown';
                }

                return false;
            }
        }

        return $isValid;
    }
}