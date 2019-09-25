<?php

namespace TickTackk\EmailListVerifyIntegration\Entity;

use TickTackk\EmailListVerifyIntegration\Globals;
use XF;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Phrase;
use function in_array;

/**
 * Class EmailListVerifyLog
 *
 * @package TickTackk\EmailListVerifyIntegration\Entity
 *
 * COLUMNS
 * @property int log_id
 * @property string email
 * @property string response
 * @property int log_date
 *
 * GETTERS
 * @property string response_phrase_name
 * @property Phrase response_phrase
 */
class EmailListVerifyLog extends Entity
{
    /**
     * @param null $error
     *
     * @return bool
     */
    public function isValid(&$error = null)
    {
        // relax..
        $isValid = in_array($this->response, ['ok', 'ok_for_all', 'accept_all'], true);

        if (!$isValid)
        {
            $error = $this->response_phrase;
            Globals::$emailValidationError = $error;
        }

        return $isValid;
    }

    /**
     * @return string
     */
    public function getResponsePhraseName() : string
    {
        $responseKey = implode('_', explode(" ", $this->response));

        return 'tckEmailListVerifyIntegration_response.' . $responseKey;
    }

    /**
     * @return Phrase
     */
    public function getResponsePhrase() : Phrase
    {
        return XF::phrase($this->response_phrase_name);
    }

    /**
     * @param Structure $structure
     *
     * @return Structure
     */
    public static function getStructure(Structure $structure) : Structure
    {
        $structure->shortName = 'TickTackk\EmailListVerifyIntegration:EmailListVerifyLog';
        $structure->table = 'xf_tck_email_list_verify_log';
        $structure->primaryKey = 'log_id';
        $structure->columns = [
            'log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'email' => ['type' => self::STR, 'required' => true],
            'response' => ['type' => self::STR, 'required' => true],
            'log_date' => ['type' => self::UINT, 'default' => XF::$time]
        ];
        $structure->getters = [
            'response_phrase_name' => true,
            'response_phrase' => true
        ];

        return $structure;
    }
}