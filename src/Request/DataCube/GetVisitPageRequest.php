<?php

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use Carbon\CarbonInterface;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 数据统计
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getVisitPage.html#调用方式
 */
class GetVisitPageRequest extends WithAccountRequest
{
    private CarbonInterface $beginDate;

    private CarbonInterface $endDate;

    public function getRequestPath(): string
    {
        // return '/datacube/getweanalysisappidvisitpage?access_token=68_aj4rB_10kIhbCUYUShFxSXgpdPIVvZLv0Z5Nm5S_UvoGsEow7yQJEALjNc9IjQfMoZ2ToY_QZYZ5ZsWAzP69_ImyTc6S31gApxxFio8AE3j1V6tGN85ubo65k0sAOJgACAUID';
        return '/datacube/getweanalysisappidvisitpage';
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
