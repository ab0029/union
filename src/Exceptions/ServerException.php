<?php

namespace Young\Union\Exceptions;

use RuntimeException;
use Young\Union\SDK;
use Young\Union\Result\Result;

/**
 * Class ServerException
 *
 * @package   Young\Union\Exception
 */
class ServerException extends BaseException
{

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var Result
     */
    protected $result;

    /**
     * ServerException constructor.
     *
     * @param Result|null $result
     * @param string      $errorMessage
     * @param string      $errorCode
     */
    public function __construct(
        Result $result,
        $errorMessage = SDK::RESPONSE_EMPTY,
        $errorCode = SDK::SERVICE_UNKNOWN_ERROR
    ) {
        $this->result       = $result;
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
        $this->resolvePropertiesByReturn();
        $this->bodyAsErrorMessage();

        parent::__construct(
            $this->getMessageString(),
            $this->result->getStatusCode()
        );
    }

    /**
     * Resolve the error message based on the return of the server.
     *
     * @return void
     */
    private function resolvePropertiesByReturn()
    {
        if ( method_exists($this->result, 'getErrorMessage') ) {
            $this->errorMessage = $this->result->getErrorMessage();
        }
        if ( method_exists($this->result, 'getErrorCode') ) {
            $this->errorCode = $this->result->getErrorCode();
        }
        if ( method_exists($this->result, 'getRequestId') ) {
            $this->requestId = $this->result->getRequestId();
        }
        if (isset($this->result['message'])) {
            $this->errorMessage = $this->result['message'];
            $this->errorCode    = $this->result['code'];
        }
        if (isset($this->result['Message'])) {
            $this->errorMessage = $this->result['Message'];
            $this->errorCode    = $this->result['Code'];
        }
        if (isset($this->result['errorMsg'])) {
            $this->errorMessage = $this->result['errorMsg'];
            $this->errorCode    = $this->result['errorCode'];
        }
        if (isset($this->result['requestId'])) {
            $this->requestId = $this->result['requestId'];
        }
        if (isset($this->result['RequestId'])) {
            $this->requestId = $this->result['RequestId'];
        }
    }

    /**
     * If the error message matches the default message and
     * the server has returned content, use the return content
     */
    private function bodyAsErrorMessage()
    {
        $body = (string)$this->result->getBody();
        if ($this->errorMessage === SDK::RESPONSE_EMPTY && $body) {
            $this->errorMessage = $body;
        }
    }

    /**
     * Get standard exception message.
     *
     * @return string
     */
    private function getMessageString()
    {
        $message = "$this->errorCode: $this->errorMessage RequestId: $this->requestId";

        if ($this->getResult()->getRequest()) {
            $method  = $this->getResult()->getRequest()->method;
            $uri     = (string)$this->getResult()->getRequest()->uri;
            $message .= " $method \"$uri\"";
            if ($this->result) {
                $message .= ' ' . $this->result->getStatusCode();
            }
        }

        return $message;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }
}
