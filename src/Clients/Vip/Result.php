<?php

namespace Young\Union\Clients\Vip;

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

        if ( isset($result_data['success']) ) {
            // 响应成功
            $result_data = $result_data['success'];
        }

        $this->dot($result_data);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isStatusSuccess() && !$this->isEmpty() && !isset($this['returnCode']);
    }

    public function getErrorMessage()
    {
        return $this['returnMessage'] ?? \Young\Union\SDK::SERVICE_UNKNOWN_ERROR;
    }

    public function getErrorCode()
    {
        return $this['returnCode'];
    }
}