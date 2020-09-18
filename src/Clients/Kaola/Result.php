<?php

namespace Young\Union\Clients\Kaola;

use Pimple\Container;
use Young\Union\Result\Result as BaseResult;

class Result extends BaseResult
{
    private $isSuccess = true;

    private $errorMessage;

    protected function resolveData()
    {
        $content = $this->getBody()->getContents();
        if ( $content == 'sign incorrect' ) {
            $this->isSuccess = false;
            $this->errorMessage = 'sign incorrect';
            $this->dot([]);
            return;
        }

        $result_data = $this->jsonToArray($content);

        if (!$result_data) {
            $result_data = [];
        }

        if (isset($result_data['code']) && $result_data['code'] != 200) {
            $this->isSuccess = false;
            $this->dot($result_data);
            return;
        }

        $this->dot($result_data);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isStatusSuccess() && !$this->isEmpty() && $this->isSuccess;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage ?? $this['msg'] ?? \Young\Union\SDK::SERVICE_UNKNOWN_ERROR;
    }

    public function getErrorCode()
    {
        return $this['code'];
    }
}