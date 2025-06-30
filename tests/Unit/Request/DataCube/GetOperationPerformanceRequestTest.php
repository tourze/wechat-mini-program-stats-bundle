<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Request\DataCube\GetOperationPerformanceRequest;

class GetOperationPerformanceRequestTest extends TestCase
{
    private GetOperationPerformanceRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetOperationPerformanceRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/wxaapi/log/get_performance', $this->request->getRequestPath());
    }

    public function testDefaultStartTime(): void
    {
        $timestamp = 1640908800;
        $this->request->setDefaultStartTime($timestamp);
        $this->assertSame($timestamp, $this->request->getDefaultStartTime());
    }

    public function testDefaultEndTime(): void
    {
        $timestamp = 1640995200;
        $this->request->setDefaultEndTime($timestamp);
        $this->assertSame($timestamp, $this->request->getDefaultEndTime());
    }

    public function testDevice(): void
    {
        $device = 'iPhone';
        $this->request->setDevice($device);
        $this->assertSame($device, $this->request->getDevice());
    }

    public function testScene(): void
    {
        $scene = '1001';
        $this->request->setScene($scene);
        $this->assertSame($scene, $this->request->getScene());
    }
}