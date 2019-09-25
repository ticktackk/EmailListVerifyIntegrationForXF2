<?php

namespace TickTackk\EmailListVerifyIntegration\XF\Admin\Controller;

use TickTackk\EmailListVerifyIntegration\Entity\EmailListVerifyLog as EmailListVerifyLogEntity;
use XF;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception as ExceptionReply;
use XF\Mvc\Reply\View as ViewReply;
use XF\Mvc\Reply\Reroute as RerouteReply;
use XF\Mvc\Reply\Redirect as RedirectReply;
use TickTackk\EmailListVerifyIntegration\Repository\EmailListVerifyLog as EmailListVerifyLogRepo;
use XF\PrintableException;

/**
 * Class Log
 *
 * @package TickTackk\EmailListVerifyIntegration\XF\Admin\Controller
 */
class Log extends XFCP_Log
{
    /**
     * @param ParameterBag $parameterBag
     *
     * @return RerouteReply|ViewReply
     */
    public function actionEmailListVerify(ParameterBag $parameterBag)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        if ($parameterBag->log_id)
        {
            return $this->rerouteController(__CLASS__, 'emailListVerifyView', $parameterBag);
        }

        $page = $this->filterPage();
        $perPage = 50;

        $logs = $this->getEmailListVerifyLogFinder()->limitByPage($page, $perPage);

        $viewParams = [
            'logs' => $logs->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $logs->total()
        ];
        return $this->view(
            'TickTackk\EmailListVerifyIntegration\XF:Log\EmailListVerification\Listing',
            'tckEmailListVerifyIntegration_log_list',
            $viewParams
        );
    }

    /**
     * @param ParameterBag $parameterBag
     *
     * @return ViewReply
     * @throws ExceptionReply
     */
    public function actionEmailListVerifyView(ParameterBag $parameterBag) : ViewReply
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $log = $this->assertEmailListVerifyLogExists($parameterBag->log_id);

        $viewParams = [
            'log' => $log
        ];
        return $this->view(
            'TickTackk\EmailListVerifyIntegration\XF:Log\EmailListVerification\View',
            'tckEmailListVerifyIntegration_log_view',
            $viewParams
        );
    }

    /**
     * @param ParameterBag $parameterBag
     *
     * @return RedirectReply
     * @throws ExceptionReply
     * @throws PrintableException
     */
    public function actionEmailListVerifyRefresh(ParameterBag $parameterBag) : RedirectReply
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $log = $this->assertEmailListVerifyLogExists($parameterBag->log_id);

        $emailListVerifyRepo = $this->getEmailListVerifyRepo();
        $newLog = $emailListVerifyRepo->refreshLog($log);

        if (!$newLog)
        {
            throw $this->exception($this->error(XF::phrase('tckEmailListVerifyIntegration_unable_to_refresh_email_validation_status')));
        }

        return $this->redirect($this->buildLink('logs/email-list-verify', $newLog));
    }

    /**
     * @param int $id
     * @param array|null $with
     * @param string|null $phraseKey
     *
     * @return Entity|EmailListVerifyLogEntity
     * @throws ExceptionReply
     */
    protected function assertEmailListVerifyLogExists(int $id, array $with = null, string $phraseKey = 'requested_log_entry_not_found') : EmailListVerifyLogEntity
    {
        return $this->assertRecordExists('TickTackk\EmailListVerifyIntegration:EmailListVerifyLog', $id, $with, $phraseKey);
    }

    /**
     * @return Finder
     */
    protected function getEmailListVerifyLogFinder() : Finder
    {
        return $this->finder('TickTackk\EmailListVerifyIntegration:EmailListVerifyLog')->setDefaultOrder('log_id', 'DESC');
    }

    /**
     * @return Repository|EmailListVerifyLogRepo
     */
    protected function getEmailListVerifyRepo() : EmailListVerifyLogRepo
    {
        return $this->repository('TickTackk\EmailListVerifyIntegration:EmailListVerifyLog');
    }
}