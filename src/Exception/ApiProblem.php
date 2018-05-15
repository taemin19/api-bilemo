<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation-error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid-body-format';

    private static $titles = array(
        self::TYPE_VALIDATION_ERROR => 'Validation error(s) found',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
    );

    private $statusCode;
    private $type;
    private $title;
    private $extraData = [];

    public function __construct($statusCode, $type = null)
    {
        $this->statusCode = $statusCode;

        if ($type === null) {
            $type = 'about:blank';
            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code.';
        } else {
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type '.$type);
            }
            $title = self::$titles[$type];
        }

        $this->type = $type;
        $this->title = $title;
    }

    public function toArray()
    {
        return array_merge(
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            ),
            $this->extraData
        );
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
    public function getTitle()
    {
        return $this->title;
    }
}
