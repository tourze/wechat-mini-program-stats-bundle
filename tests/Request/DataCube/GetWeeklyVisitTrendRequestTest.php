<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWeeklyVisitTrendRequest;

/**
 * @internal
 */
#[CoversClass(GetWeeklyVisitTrendRequest::class)]
final class GetWeeklyVisitTrendRequestTest extends RequestTestCase
{
    private GetWeeklyVisitTrendRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWeeklyVisitTrendRequest();
    }

    public function testGetRequestPath(): void
    {
        self::assertSame('/datacube/getweanalysisappidweeklyvisittrend', $this->request->getRequestPath());
    }

    public function testBeginDate(): void
    {
        $date = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-01') ?? CarbonImmutable::now();
        $this->request->setBeginDate($date);
        self::assertSame($date, $this->request->getBeginDate());
    }

    public function testEndDate(): void
    {
        $date = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-31') ?? CarbonImmutable::now();
        $this->request->setEndDate($date);
        self::assertSame($date, $this->request->getEndDate());
    }

    public function testGetRequestOptions(): void
    {
        $beginDate = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-01') ?? CarbonImmutable::now();
        $endDate = CarbonImmutable::createFromFormat('Y-m-d', '2023-01-31') ?? CarbonImmutable::now();

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);

        $expectedOptions = [
            'json' => [
                'begin_date' => '20230101',
                'end_date' => '20230131',
            ],
        ];

        self::assertEquals($expectedOptions, $this->request->getRequestOptions());
    }
}
