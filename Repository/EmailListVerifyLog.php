<?php

namespace TickTackk\EmailListVerifyIntegration\Repository;

use XF\Mvc\Entity\Repository;
use TickTackk\EmailListVerifyIntegration\Entity\EmailListVerifyLog as EmailListVerifyLogEntity;
use TickTackk\EmailListVerifyIntegration\Service\EmailListVerify\Verifier as EmailListVerifierSvc;
use XF\PrintableException;

/**
 * Class EmailListVerifyLog
 *
 * @package TickTackk\EmailListVerifyIntegration\Repository
 */
class EmailListVerifyLog extends Repository
{
    /**
     * @param EmailListVerifyLogEntity $emailListVerifyLog
     *
     * @return EmailListVerifyLogEntity|null
     * @throws PrintableException
     */
    public function refreshLog(EmailListVerifyLogEntity $emailListVerifyLog) :? EmailListVerifyLogEntity
    {
        /** @var EmailListVerifierSvc $emailListVerifierSvc */
        $emailListVerifierSvc = $this->app()->service('TickTackk\EmailListVerifyIntegration:EmailListVerify\Verifier', $emailListVerifyLog->email);
        $emailListVerifierSvc->setAvoidFromCache();
        $emailListVerifierSvc->verify();

        return $emailListVerifierSvc->getEmailListVerifyLog();
    }
}