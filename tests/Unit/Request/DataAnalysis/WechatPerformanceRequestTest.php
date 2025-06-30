<?php

namespace WechatMiniProgramStatsBundle\Tests\Unit\Request\DataAnalysis;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramStatsBundle\Request\DataAnalysis\WechatPerformanceRequest;

class WechatPerformanceRequestTest extends TestCase
{
    private WechatPerformanceRequest $request;

    protected function setUp(): void
    {
        $this->request = new WechatPerformanceRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/wxa/business/performance/boot', $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testEndTimestamp(): void
    {
        $timestamp = '1640995200';
        $this->request->setEndTimestamp($timestamp);
        $this->assertSame($timestamp, $this->request->getEndTimestamp());
    }

    public function testStartTimestamp(): void
    {
        $timestamp = '1640908800';
        $this->request->setStartTimestamp($timestamp);
        $this->assertSame($timestamp, $this->request->getStartTimestamp());
    }

    public function testModule(): void
    {
        $module = '10016';
        $this->request->setModule($module);
        $this->assertSame($module, $this->request->getModule());
    }

    public function testParams(): void
    {
        $params = ['param1' => 'value1', 'param2' => 'value2'];
        $this->request->setParams($params);
        $this->assertSame($params, $this->request->getParams());
    }

    public function testGetRequestOptions(): void
    {
        $startTimestamp = '1640908800';
        $endTimestamp = '1640995200';
        $module = '10016';
        $params = ['param1' => 'value1'];

        $this->request->setStartTimestamp($startTimestamp);
        $this->request->setEndTimestamp($endTimestamp);
        $this->request->setModule($module);
        $this->request->setParams($params);

        $expectedOptions = [
            'json' => [
                'time' => [
                    'end_timestamp' => intval($endTimestamp),
                    'begin_timestamp' => intval($startTimestamp),
                ],
                'module' => intval($module),
                'params' => $params,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithEmptyParams(): void
    {
        $startTimestamp = '1640908800';
        $endTimestamp = '1640995200';
        $module = '10016';

        $this->request->setStartTimestamp($startTimestamp);
        $this->request->setEndTimestamp($endTimestamp);
        $this->request->setModule($module);

        $expectedOptions = [
            'json' => [
                'time' => [
                    'end_timestamp' => intval($endTimestamp),
                    'begin_timestamp' => intval($startTimestamp),
                ],
                'module' => intval($module),
                'params' => [],
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }
}