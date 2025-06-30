<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Request\DataCube\GetMonthlyVisitTrendRequest;

class GetMonthlyVisitTrendRequestTest extends TestCase
{
    private GetMonthlyVisitTrendRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetMonthlyVisitTrendRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/datacube/getweanalysisappidmonthlyvisittrend', $this->request->getRequestPath());
    }

    public function testBeginDate(): void
    {
        $date = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-01');
        $this->request->setBeginDate($date);
        $this->assertSame($date, $this->request->getBeginDate());
    }

    public function testEndDate(): void
    {
        $date = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-31');
        $this->request->setEndDate($date);
        $this->assertSame($date, $this->request->getEndDate());
    }

    public function testGetRequestOptions(): void
    {
        $beginDate = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-01');
        $endDate = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-31');

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);

        $expectedOptions = [
            'json' => [
                'begin_date' => '20230101',
                'end_date' => '20230131',
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }
}