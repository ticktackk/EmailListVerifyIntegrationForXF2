<?php

namespace TickTackk\EmailListVerifyIntegration\Job;

use TickTackk\EmailListVerifyIntegration\XF\Entity\User AS ExtendedUserEntity;
use XF;
use XF\Job\AbstractRebuildJob;
use TickTackk\EmailListVerifyIntegration\Service\EmailListVerify\Verifier as EmailVerifierSvc;
use XF\Phrase;
use XF\PrintableException;

/**
 * Class ValidateUserEmail
 *
 * @package TickTackk\EmailListVerifyIntegration\Job
 */
class ValidateUserEmail extends AbstractRebuildJob
{
    /**
     * @param int $start
     * @param int $batch
     *
     * @return array
     */
    protected function getNextIds($start, $batch) : array
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit(
            "
                SELECT user_id
                FROM xf_user
                WHERE user_id > ?
                  AND email <> ''
                ORDER BY user_id ASC
            ", $batch
        ), $start);
    }

    /**
     * @param $id
     *
     * @throws PrintableException
     */
    protected function rebuildById($id) : void
    {
        /** @var ExtendedUserEntity $user */
        $user = $this->app->em()->find('XF:User', $id);
        if ($user && !$this->validateEmail($user->email))
        {
            $user->user_state = 'email_bounce';
            $user->save();
        }
    }

    /**
     * @param string $email
     * @param null $error
     *
     * @return bool
     * @throws PrintableException
     */
    protected function validateEmail(string $email, &$error = null) : bool
    {
        /** @var EmailVerifierSvc $verifierSvc */
        $verifierSvc = $this->app->service('TickTackk\EmailListVerifyIntegration:EmailListVerify\Verifier', $email);

        return $verifierSvc->verify($error);
    }

    /**
     * @return Phrase
     */
    protected function getStatusType() : Phrase
    {
        return XF::phrase('tckEmailListVerifyIntegration_users_email');
    }

    public function getStatusMessage()
    {
        $actionPhrase = XF::phrase('tckEmailListVerifyIntegration_validating');
        $typePhrase = $this->getStatusType();

        return sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, $this->data['start']);
    }
}