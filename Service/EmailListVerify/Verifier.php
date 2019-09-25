<?php

namespace TickTackk\EmailListVerifyIntegration\Service\EmailListVerify;

use Exception;
use GuzzleHttp\Exception\RequestException;
use TickTackk\EmailListVerifyIntegration\Entity\EmailListVerifyLog as EmailListVerifyLogEntity;
use XF;
use XF\PrintableException;
use XF\Service\AbstractService;
use XF\App;

/**
 * Class Verifier
 *
 * @package TickTackk\EmailListVerifyIntegration\Service\EmailListVerify
 */
class Verifier extends AbstractService
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $apiKey;

    /**
     * @var bool
     */
    protected $avoidFromCache;

    /**
     * @var null|EmailListVerifyLogEntity
     */
    protected $emailListVerifyLog;

    /**
     * Verifier constructor.
     *
     * @param App     $app
     * @param string      $email
     * @param string|null $apiKey
     *
     * @throws Exception
     */
    public function __construct(App $app, string $email, string $apiKey = null)
    {
        parent::__construct($app);

        $this->setEmail($email);
        $this->setApiKey($apiKey);
    }

    /**
     * @param $email
     */
    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    /**
     * @param bool|null $avoidFromCache
     */
    public function setAvoidFromCache(bool $avoidFromCache = null) : void
    {
        $this->avoidFromCache = $avoidFromCache ?: true;
    }

    /**
     * @param string|null $apiKey
     *
     * @throws Exception
     */
    public function setApiKey(string $apiKey = null) : void
    {
        if ($apiKey === null)
        {
            $apiKey = $this->app->options()->emailListVerifyAPIKey;
        }

        if (empty($apiKey))
        {
            throw new Exception('Please enter a valid key.');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * @return null|string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @param string|null $apiKey
     *
     * @return string
     */
    protected function getApiEndpointUri(string $apiKey = null) : string
    {
        if ($apiKey === null)
        {
            $apiKey = $this->getApiKey();
        }

        return "https://apps.emaillistverify.com/api/verifEmail?secret={$apiKey}&email={$this->email}";
    }

    /**
     * @return EmailListVerifyLogEntity|null
     */
    public function getEmailListVerifyLog() :? EmailListVerifyLogEntity
    {
        return $this->emailListVerifyLog;
    }

    /**
     * @param string|null $error
     *
     * @return bool
     * @throws PrintableException
     */
    public function verify(string &$error = null) : bool
    {
        if (!$this->avoidFromCache)
        {
            /** @var EmailListVerifyLogEntity $log */
            $log = $this->finder('TickTackk\EmailListVerifyIntegration:EmailListVerifyLog')
                ->where('email', $this->email)
                ->where('log_date', '>=', XF::$time - 86400)
                ->order('log_date', 'DESC')
                ->fetchOne();

            if ($log)
            {
                $this->emailListVerifyLog = $log;
            }
        }

        if (!$this->emailListVerifyLog)
        {
            $client = $this->app->http()->createClient();
            $addOns = $this->app->container('addon.cache');
            $addOnVersion = $addOns['TickTackk\EmailListVerifyIntegration'] ?? 0 >= 1000011;

            try
            {
                $request = $client->post($this->getApiEndpointUri(), [
                    'headers' => [
                        'XF-TCK-ADDON-VER' => $addOnVersion,
                    ]
                ]);

                /** @var EmailListVerifyLogEntity $log */
                $log = $this->em()->create('TickTackk\EmailListVerifyIntegration:EmailListVerifyLog');
                $log->email = $this->email;
                $log->response = $request->getBody()->getContents();
                $log->save();

                $this->emailListVerifyLog = $log;
            }
            catch (RequestException $e)
            {
                XF::logException($e, false, "Email Verification failed: {$e->getMessage()} ");
            }
        }

        if ($this->emailListVerifyLog)
        {
            return $this->emailListVerifyLog->isValid($error);
        }

        return false;
    }
}