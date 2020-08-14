<?php

namespace Young\Union\Clients\Jd;

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

        $result_data = current($result_data) ?? [];
        if ( isset($result_data['result']) ) {
            $result_data['result'] = json_decode($result_data['result'], true);
        }

        $this->dot($result_data);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->isStatusSuccess() && !$this->isEmpty() && $this['code'] == 0 && $this['result.code'] == 200;
    }

    public function getErrorMessage()
    {
        return $this->get('zh_msg', $this->get('result.message', \Young\Union\SDK::SERVICE_UNKNOWN_ERROR));
    }

    public function getErrorCode()
    {
        return $this['result.code'] ?? $this['code'];
    }

    public function getRequestId()
    {
        return $this['result.requestId'];
    }
}