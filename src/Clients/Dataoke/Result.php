<?php

namespace Young\Union\Clients\Dataoke;

use Pimple\Container;
use Young\Union\Result\Result as BaseResult;

class Result extends BaseResult
{
    private $isSuccess = true;

    protected function resolveData()
    {
        $content = $this->getBody()->getContents();

        $result_data = $this->jsonToArray($content);

        if (!$result_data) {
            $result_data = [];
        }

        if ( isset($result_data['code']) && $result_data['code'] !== 0 ) {
            $this->dot($result_data);
            $this->isSuccess = false;
            return;
        }

        if ( isset($result_data['data']['error_response']) ) {
            $this->dot($result_data['data']['error_response']);
            $this->isSuccess = false;
            return;
        }

        $this->dot($result_data['data'] ?? []);
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
        return $this['msg'] ?? \Young\Union\SDK::SERVICE_UNKNOWN_ERROR;
    }

    public function getErrorCode()
    {
        return $this['code'];
    }
}