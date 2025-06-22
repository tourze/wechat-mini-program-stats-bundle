<?php

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatUserPortraitRequest;

class GetWechatUserPortraitRequestTest extends TestCase
{
    private GetWechatUserPortraitRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWechatUserPortraitRequest();
    }

    public function testGetRequestPath_returnsCorrectPath(): void
    {
        $this->assertEquals(
            '/datacube/getweanalysisappiduserportrait',
            $this->request->getRequestPath()
        );
    }

    public function testGetRequestOptions_returnsCorrectlyFormattedOptions(): void
    {
        $beginDate = CarbonImmutable::parse('2023-01-01');
        $endDate = CarbonImmutable::parse('2023-01-07');
        
        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('begin_date', $options['json']);
        $this->assertArrayHasKey('end_date', $options['json']);
        $this->assertEquals('20230101', $options['json']['begin_date']);
        $this->assertEquals('20230107', $options['json']['end_date']);
    }

    public function testBeginDate_getterAndSetter(): void
    {
        $beginDate = CarbonImmutable::parse('2023-01-01');
        
        $this->request->setBeginDate($beginDate);
        
        $this->assertSame($beginDate, $this->request->getBeginDate());
    }

    public function testEndDate_getterAndSetter(): void
    {
        $endDate = CarbonImmutable::parse('2023-01-07');
        
        $this->request->setEndDate($endDate);
        
        $this->assertSame($endDate, $this->request->getEndDate());
    }

    public function testWithAccount_setsAccountCorrectly(): void
    {
        $account = $this->createMock(Account::class);
        
        $this->request->setAccount($account);
        
        $this->assertSame($account, $this->request->getAccount());
    }
} 