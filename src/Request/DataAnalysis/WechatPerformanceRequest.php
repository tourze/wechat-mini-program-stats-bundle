<?php

namespace WechatMiniProgramStatsBundle\Request\DataAnalysis;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getPerformanceData.html
 */
class WechatPerformanceRequest extends WithAccountRequest
{
    private string $endTimestamp = '';

    private string $startTimestamp = '';

    private string $module;

    private array $params = [];

    public function getRequestPath(): string
    {
        return '/wxa/business/performance/boot';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'time' => [   // 开始和结束日期的时间戳，时间跨度不能超过30天
                    'end_timestamp' => intval($this->getEndTimestamp()),
                    'begin_timestamp' => intval($this->getStartTimestamp()),
                ],
                'module' => intval($this->getModule()),
                'params' => $this->getParams(),
            ],
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    public function getEndTimestamp(): string
    {
        return $this->endTimestamp;
    }

    public function setEndTimestamp(string $endTimestamp): void
    {
        $this->endTimestamp = $endTimestamp;
    }

    public function getStartTimestamp(): string
    {
        return $this->startTimestamp;
    }

    public function setStartTimestamp(string $startTimestamp): void
    {
        $this->startTimestamp = $startTimestamp;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}
