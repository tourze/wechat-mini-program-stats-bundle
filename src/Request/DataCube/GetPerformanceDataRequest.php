<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 数据统计
 */
class GetPerformanceDataRequest extends WithAccountRequest
{
    /** @var array<string, array<string, string>> */
    private array $params;

    private object $time;

    private int $module;

    public function getRequestPath(): string
    {
        return '/wxa/business/performance/boot';
    }

    /**
     * @return array<string, array<string, mixed>>|null
     */
    /**
     * @return array<string, mixed>|null
     */
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

    /**
     * @return array<string, array<string, string>>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<string, array<string, string>> $params
     */
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
