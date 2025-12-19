<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\PHPUnitJsonRPC\AbstractProcedureTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;
use WechatMiniProgramStatsBundle\Param\GetWechatMiniProgramRetainUserByDateParam;
use WechatMiniProgramStatsBundle\Procedure\GetWechatMiniProgramRetainUserByDate;

/**
 * @internal
 */
#[CoversClass(GetWechatMiniProgramRetainUserByDate::class)]
#[RunTestsInSeparateProcesses]
final class GetWechatMiniProgramRetainUserByDateTest extends AbstractProcedureTestCase
{
    protected function onSetUp(): void
    {
        // 不要调用 parent::setUp()，避免无限递归
    }

    public function testProcedureExists(): void
    {
        self::assertTrue(class_exists(GetWechatMiniProgramRetainUserByDate::class));
    }

    public function testExecuteWithInvalidAccount(): void
    {
        $procedure = self::getService(GetWechatMiniProgramRetainUserByDate::class);
        $param = new GetWechatMiniProgramRetainUserByDateParam(
            accountId: 'invalid-account',
            date: '2023-01-01',
        );

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('找不到小程序');

        $procedure->execute($param);
    }

    public function testExecuteWithValidData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('valid-account');
        $account->setAppSecret('test-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $retainData = new DailyRetainData();
        $retainData->setAccount($account);
        $retainData->setDate(new \DateTimeImmutable('2023-01-01'));
        $retainData->setUserNumber('100');
        self::getEntityManager()->persist($retainData);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramRetainUserByDate::class);
        $param = new GetWechatMiniProgramRetainUserByDateParam(
            accountId: (string) $account->getId(),
            date: '2023-01-01',
        );

        $result = $procedure->execute($param);

        $expected = [
            'visitUv' => 0,
            'visitUvNew' => 0,
            'visitUvCompare' => null,
            'visitUvNewCompare' => null,
            'visitUvSevenCompare' => null,
            'visitUvNewSevenCompare' => null,
        ];

        self::assertEquals($expected, $result->toArray());
    }

    public function testExecuteWithNoData(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('valid-account');
        $account->setAppSecret('test-secret');
        $account->setValid(true);
        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $procedure = self::getService(GetWechatMiniProgramRetainUserByDate::class);
        $param = new GetWechatMiniProgramRetainUserByDateParam(
            accountId: (string) $account->getId(),
            date: '2023-01-01',
        );

        $result = $procedure->execute($param);

        self::assertEquals([
            'visitUv' => 0,
            'visitUvNew' => 0,
            'visitUvCompare' => null,
            'visitUvNewCompare' => null,
            'visitUvSevenCompare' => null,
            'visitUvNewSevenCompare' => null,
        ], $result->toArray());
    }
}
