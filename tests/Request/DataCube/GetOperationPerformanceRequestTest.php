<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramStatsBundle\Request\DataCube\GetOperationPerformanceRequest;

/**
 * @internal
 */
#[CoversClass(GetOperationPerformanceRequest::class)]
final class GetOperationPerformanceRequestTest extends RequestTestCase
{
    private GetOperationPerformanceRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetOperationPerformanceRequest();
    }

    public function testGetRequestPath(): void
    {
        self::assertSame('/wxaapi/log/get_performance', $this->request->getRequestPath());
    }

    public function testDefaultStartTime(): void
    {
        $timestamp = 1640908800;
        $this->request->setDefaultStartTime($timestamp);
        self::assertSame($timestamp, $this->request->getDefaultStartTime());
    }

    public function testDefaultEndTime(): void
    {
        $timestamp = 1640995200;
        $this->request->setDefaultEndTime($timestamp);
        self::assertSame($timestamp, $this->request->getDefaultEndTime());
    }

    public function testDevice(): void
    {
        $device = 'iPhone';
        $this->request->setDevice($device);
        self::assertSame($device, $this->request->getDevice());
    }

    public function testScene(): void
    {
        $scene = '1001';
        $this->request->setScene($scene);
        self::assertSame($scene, $this->request->getScene());
    }
}
