<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public int $httpStatus;
    public ?array $details;
    public string $codeStr;
    public function __construct(
        string $message = 'Error',
        int $httpStatus = 500,
        ?array $details = null,
        string $codeStr = 'server_error',
        int $code = 0
    ) {
        parent::__construct($message, $code);
        $this->httpStatus =  $httpStatus;
        $this->details = $details;
        $this->codeStr = $codeStr;
    }
}
