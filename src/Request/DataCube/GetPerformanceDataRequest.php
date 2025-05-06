<?php

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 数据统计
 */
class GetPerformanceDataRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/wxa/business/performance/boot';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'module' => $this->getModule(),
            'time' => $this->getTime(),
            'params' => $this->getParams(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getTime(): object
    {
        return $this->time;
    }

    public function setTime(object $time): void
    {
        $this->time = $time;
    }

    public function getModule(): int
    {
        return $this->module;
    }

    public function setModule(int $module): void
    {
        $this->module = $module;
    }
}
