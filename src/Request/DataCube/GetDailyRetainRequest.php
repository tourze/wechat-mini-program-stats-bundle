<?php

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 数据统计
 */
class GetDailyRetainRequest extends WithAccountRequest
{
    private CarbonInterface $beginDate;

    private CarbonInterface $endDate;

    public function getRequestPath(): string
    {
        return '/datacube/getweanalysisappiddailyretaininfo';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'begin_date' => $this->getBeginDate()->format('Ymd'),
            'end_date' => $this->getEndDate()->format('Ymd'),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getBeginDate(): CarbonInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(CarbonInterface $beginDate): void
    {
        $this->beginDate = $beginDate;
    }

    public function getEndDate(): CarbonInterface
    {
        return $this->endDate;
    }

    public function setEndDate(CarbonInterface $endDate): void
    {
        $this->endDate = $endDate;
    }
}
