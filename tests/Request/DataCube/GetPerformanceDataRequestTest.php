<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramStatsBundle\Request\DataCube\GetPerformanceDataRequest;

/**
 * @internal
 */
#[CoversClass(GetPerformanceDataRequest::class)]
final class GetPerformanceDataRequestTest extends RequestTestCase
{
    private GetPerformanceDataRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetPerformanceDataRequest();
    }

    public function testGetRequestPath(): void
    {
        self::assertSame('/wxa/business/performance/boot', $this->request->getRequestPath());
    }

    public function testModule(): void
    {
        $module = 10016;
        $this->request->setModule($module);
        self::assertSame($module, $this->request->getModule());
    }

    public function testParams(): void
    {
        $params = ['param1' => ['subparam1' => 'value1']];
        $this->request->setParams($params);
        self::assertSame($params, $this->request->getParams());
    }

    public function testTime(): void
    {
        $time = (object) ['start' => 1640908800, 'end' => 1640995200];
        $this->request->setTime($time);
        self::assertEquals($time, $this->request->getTime());
    }
}
