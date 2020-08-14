<?php

namespace Young\Union\Exceptions;

use Exception;
use RuntimeException;

/**
 * Class ClientException
 *
 * @package   Young\Union\Exceptions
 */
class ClientException extends BaseException
{

    /**
     * ClientException constructor.
     *
     * @param string         $errorMessage
     * @param string         $errorCode
     * @param Exception|null $previous
     */
    public function __construct($errorMessage, $errorCode, $previous = null)
    {
        parent::__construct($errorMessage, 0, $previous);
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

    /**
     * @codeCoverageIgnore
     * @deprecated
     */
    public function getErrorType()
    {
        return 'Client';
    }
}
