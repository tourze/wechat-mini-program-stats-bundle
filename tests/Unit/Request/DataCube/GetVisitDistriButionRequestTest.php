<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Request\DataCube\GetVisitDistriButionRequest;

class GetVisitDistriButionRequestTest extends TestCase
{
    private GetVisitDistriButionRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetVisitDistriButionRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/datacube/getweanalysisappidvisitdistribution', $this->request->getRequestPath());
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
                'begin_date' => '20230425',
                'end_date' => '20230425',
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }
}