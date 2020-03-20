<?php

namespace NextcloudApiWrapper;

use Symfony\Contracts\HttpClient\ResponseInterface;

class NCException extends \Exception
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response, $message = "", $code = 0, \Throwable $previous = null)
    {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}