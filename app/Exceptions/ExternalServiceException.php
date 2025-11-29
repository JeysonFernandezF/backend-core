<?php

namespace App\Exceptions;

use Exception;

class ExternalServiceException extends ApiException
{
    public function __construct(string $serviceName, string $message = 'Error de servicio externo', ?array $details = null, int $httpStatus = 502)
    {
        $msg = "{$serviceName}: {$message}";
        parent::__construct($msg, $httpStatus, $details, 'external_service_error');
    }
}
