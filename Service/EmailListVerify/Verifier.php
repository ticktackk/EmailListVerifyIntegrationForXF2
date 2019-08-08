<?php

namespace TickTackk\EmailListVerifyIntegration\Service\EmailListVerify;

use XF\Service\AbstractService;

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
     * Verifier constructor.
     *
     * @param \XF\App     $app
     * @param string      $email
     * @param string|null $apiKey
     *
     * @throws \Exception
     */
    public function __construct(\XF\App $app, string $email, string $apiKey = null)
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
     * @param string|null $apiKey
     *
     * @throws \Exception
     */
    public function setApiKey(string $apiKey = null) : void
    {
        if ($apiKey === null)
        {
            $apiKey = $this->app->options()->emailListVerifyAPIKey;
        }

        if (empty($apiKey))
        {
            throw new \Exception('Please enter a valid key.');
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
     * @param null $apiKey
     *
     * @return string
     */
    protected function getEndpointUri(string $apiKey = null) : string
    {
        if ($apiKey === null)
        {
            $apiKey = $this->getApiKey();
        }

        return "https://apps.emaillistverify.com/api/verifEmail?secret={$apiKey}&email={$this->email}";
    }

    /**
     * @return array
     */
    protected function getPassedResponses() : array
    {
        return ['all is ok'];
    }

    /**
     * @param string|null $error
     *
     * @return bool
     */
    public function verify(string &$error = null) : bool
    {
        $request = $this->app->http()->reader()->getUntrusted($this->getEndpointUri(), [], null, [
            'timeout' => 5
        ]);

        if (!$request || $request->getStatusCode() !== 200)
        {
            return false;
        }

        $response = (string)$request->getBody();
        if (\in_array($response, $this->getPassedResponses(), true))
        {
            return true;
        }

        $error = $response;
        return false;
    }
}