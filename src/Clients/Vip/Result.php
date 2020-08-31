<?php

namespace Young\Union\Clients\Vip;

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

        if ( $result_data['returnCode'] != 0 ) {
            $this->dot($result_data);
            $this->isSuccess = false;
            return;
        }

        if ( isset($result_data['result']) ) {
            // 响应成功
            $result_data = $result_data['result'];
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
        return $this['returnMessage'] ?? \Young\Union\SDK::SERVICE_UNKNOWN_ERROR;
    }

    public function getErrorCode()
    {
        return $this['returnCode'];
    }
}