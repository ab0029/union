<?php

namespace Young\Union\Clients\Taobao;

use Pimple\Container;
use Young\Union\Result\Result as BaseResult;

class Result extends BaseResult
{
    protected function resolveData()
    {
        $content = $this->getBody()->getContents();

        $result_data = $this->jsonToArray($content);

        if (!$result_data) {
            $result_data = [];
        }

        if ( !isset($result_data['error_response']) ) {
            $result_data = current($result_data) ?? [];
        }

        $this->dot($result_data);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isStatusSuccess() && !$this->isEmpty() && !isset($this['error_response']);
    }

    public function getErrorMessage()
    {
        return $this['error_response.sub_msg'] ?? $this['error_response.msg'] ?? \Young\Union\SDK::SERVICE_UNKNOWN_ERROR;
    }

    public function getErrorCode()
    {
        return $this['error_response.sub_code'] ?? $this['error_response.code'];
    }

    public function getRequestId()
    {
        return $this['error_response.request_id'] ?? $this['request_id'];
    }
}