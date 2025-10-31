<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitCityDataCrudController;
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

/**
 * UserPortraitCityDataCrudController 测试
 *
 * @internal
 */
#[CoversClass(UserPortraitCityDataCrudController::class)]
#[RunTestsInSeparateProcesses]
final class UserPortraitCityDataCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): UserPortraitCityDataCrudController
    {
        return self::getService(UserPortraitCityDataCrudController::class);
    }

    /**
     * 提供新增页字段数据（只读控制器，操作已禁用）
     *
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account']; // 占位数据，NEW操作已被禁用
    }

    /**
     * 提供编辑页字段数据（只读控制器，操作已禁用）
     *
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'account' => ['account']; // 占位数据，EDIT操作已被禁用
    }

    public function testEntityFqcn(): void
    {
        $this->assertSame(UserPortraitCityData::class, UserPortraitCityDataCrudController::getEntityFqcn());
    }

    public function testControllerIsSubclassOfAbstractCrud(): void
    {
        $this->assertInstanceOf(AbstractCrudController::class, $this->getControllerService());
    }

    /**
     * 提供索引页表头数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '小程序账号' => ['小程序账号'];
        yield '日期' => ['日期'];
        yield '类型' => ['类型'];
        yield '名称' => ['名称'];
        yield '值' => ['值'];
        yield '值ID' => ['值ID'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * 提供详情页字段数据
     *
     * @return iterable<string, array{string}>
     */
    public static function provideDetailPageFields(): iterable
    {
        yield 'id' => ['id'];
        yield 'account' => ['account'];
        yield 'createTime' => ['createTime'];
    }

    public function testValidationErrors(): void
    {
        // Controller禁用了NEW和EDIT操作，无法测试表单验证
        // 正常情况下应验证提交空表单时返回 "should not be blank" 错误
        $controller = $this->getControllerService();

        // 验证Controller确实定义了必填字段
        $fields = iterator_to_array($controller->configureFields('new'));
        $this->assertNotEmpty($fields, 'Controller should have field configuration');

        // 由于禁用了表单操作，此测试仅验证字段配置存在
        self::markTestSkipped('Form operations are disabled for this read-only controller');
    }
}
