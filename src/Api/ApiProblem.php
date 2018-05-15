<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    private $statusCode;
    private $type;
    private $title;
    private $extraData = [];

    public function __construct($statusCode)
    {
        $this->statusCode = $statusCode;

        $type = 'about:blank';
        $title = isset(Response::$statusTexts[$statusCode])
            ? Response::$statusTexts[$statusCode]
            : 'Unknown status code';

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
