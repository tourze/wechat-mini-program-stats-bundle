<?php

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 获取用户访问小程序数据周趋势
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/visit-trend/getWeeklyVisitTrend.html
 */
class GetWeeklyVisitTrendRequest extends WithAccountRequest
{
    private CarbonInterface $beginDate;

    private CarbonInterface $endDate;

    public function getRequestPath(): string
    {
        return '/datacube/getweanalysisappidweeklyvisittrend';
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
