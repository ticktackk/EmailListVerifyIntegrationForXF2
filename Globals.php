<?php

namespace TickTackk\EmailListVerifyIntegration;

use XF\Phrase;

/**
 * Class Globals
 *
 * @package TickTackk\EmailListVerifyIntegration
 */
class Globals
{
    /**
     * @var null|bool
     */
    public static $useEmailListVerify;

    /**
     * @var null|Phrase
     */
    public static $emailValidationError;

    /**
     * Globals constructor.
     */
    private function __construct() { }
}