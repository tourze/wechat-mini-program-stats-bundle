<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Request\DataCube\GetPerformanceDataRequest;

class GetPerformanceDataRequestTest extends TestCase
{
    private GetPerformanceDataRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetPerformanceDataRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/wxa/business/performance/boot', $this->request->getRequestPath());
    }

    public function testModule(): void
    {
        $module = 10016;
        $this->request->setModule($module);
        $this->assertSame($module, $this->request->getModule());
    }

    public function testParams(): void
    {
        $params = ['param1' => 'value1'];
        $this->request->setParams($params);
        $this->assertSame($params, $this->request->getParams());
    }

    public function testTime(): void
    {
        $time = (object) ['start' => 1640908800, 'end' => 1640995200];
        $this->request->setTime($time);
        $this->assertEquals($time, $this->request->getTime());
    }
}