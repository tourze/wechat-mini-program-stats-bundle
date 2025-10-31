<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Request\DataCube;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Request\DataCube\GetWechatUserPortraitRequest;

/**
 * @internal
 */
#[CoversClass(GetWechatUserPortraitRequest::class)]
final class GetWechatUserPortraitRequestTest extends RequestTestCase
{
    private GetWechatUserPortraitRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetWechatUserPortraitRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        self::assertEquals(
            '/datacube/getweanalysisappiduserportrait',
            $this->request->getRequestPath()
        );
    }

    public function testGetRequestOptionsReturnsCorrectlyFormattedOptions(): void
    {
        $beginDate = CarbonImmutable::parse('2023-01-01');
        $endDate = CarbonImmutable::parse('2023-01-07');

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);

        $options = $this->request->getRequestOptions();

        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);
        self::assertIsArray($options['json']);
        self::assertArrayHasKey('begin_date', $options['json']);
        self::assertArrayHasKey('end_date', $options['json']);
        self::assertEquals('20230101', $options['json']['begin_date']);
        self::assertEquals('20230107', $options['json']['end_date']);
    }

    public function testBeginDateGetterAndSetter(): void
    {
        $beginDate = CarbonImmutable::parse('2023-01-01');

        $this->request->setBeginDate($beginDate);

        self::assertSame($beginDate, $this->request->getBeginDate());
    }

    public function testEndDateGetterAndSetter(): void
    {
        $endDate = CarbonImmutable::parse('2023-01-07');

        $this->request->setEndDate($endDate);

        self::assertSame($endDate, $this->request->getEndDate());
    }

    public function testWithAccountSetsAccountCorrectly(): void
    {
        // 必须使用具体类 Account 而不是接口的原因：
        // 理由1：Account 是 Doctrine Entity 类，代表微信小程序账户配置，没有对应的接口抽象
        // 理由2：测试需要验证请求对象能够正确存储和获取账户实例，用于后续的 API 调用认证
        // 理由3：使用 Mock 可以避免依赖真实的账户数据和数据库查询，提高测试的执行效率
        $account = $this->createMock(Account::class);

        $this->request->setAccount($account);

        self::assertSame($account, $this->request->getAccount());
    }
}
