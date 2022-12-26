<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiNoDataExistException extends Exception
{
    protected $message;
    protected $statusCode = Response::HTTP_BAD_REQUEST;

    public function __construct()
    {
        parent::__construct();
        $this->message = '該当のタスクは見つかりませんでした。';
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
