<?php

namespace TickTackk\EmailListVerifyIntegration;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Create;

/**
 * Class Setup
 *
 * @package TickTackk\EmailListVerifyIntegration
 */
class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    public function installStep1() : void
    {
        $sm = $this->schemaManager();

        $sm->createTable('xf_tck_email_list_verify_log', function (Create $table)
        {
            $table->addColumn('log_id', 'int')->autoIncrement();
            $table->addColumn('email', 'varchar', 120);
            $table->addColumn('response', 'text');
            $table->addColumn('log_date', 'int');

            $table->addKey('log_date');
        });
    }

    public function upgrade1000070Step1() : void
    {
        $this->installStep1();
    }

    public function uninstallStep1() : void
    {
        $sm = $this->schemaManager();

        $sm->dropTable('xf_tck_email_list_verify_log');
    }
}