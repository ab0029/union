<?php

namespace Young\Union\Clients\Jd;

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

        $result_data = current($result_data) ?? [];
        if ( isset($result_data['result']) ) {
            $result_data['result'] = json_decode($result_data['result'], true);
        }

        if ( $result_data['code'] != 0 || (isset($result_data['result']['code']) && $result_data['result']['code'] != 200) ) {
            $this->dot($result_data);
            $this->isSuccess = false;
            return;
        }

        $this->dot($result_data['result']);
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
        return $this->get('zh_desc', $this->get('result.message', \Young\Union\SDK::SERVICE_UNKNOWN_ERROR));
    }

    public function getErrorCode()
    {
        return $this['result.code'] ?? $this['code'];
    }

    public function getRequestId()
    {
        return $this['result.requestId'] ?? $this['requestId'];
    }
}