<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use Carbon\CarbonImmutable;
use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatMiniUserAccessesWeekDataRequest;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniUserAccessesWeekDataRequest::class)]
final class GetWechatMiniUserAccessesWeekDataRequestTest extends RequestTestCase
{
    private GetWechatMiniUserAccessesWeekDataRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWechatMiniUserAccessesWeekDataRequest();
    }

    public function testGetRequestPath(): void
    {
        self::assertSame('/datacube/getweanalysisappidweeklyretaininfo', $this->request->getRequestPath());
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
