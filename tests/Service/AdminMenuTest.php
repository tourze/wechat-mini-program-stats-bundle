<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatMiniProgramStatsBundle\Service\AdminMenu;

/**
 * AdminMenu 服务单元测试
 * 测试重点：菜单项配置、菜单结构正确性
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testInvokeCreatesCorrectMenuStructure(): void
    {
        $mockMenu = $this->createMock(ItemInterface::class);
        $mockStatsMenu = $this->createMock(ItemInterface::class);

        $mockMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('微信小程序统计数据')
            ->willReturnOnConsecutiveCalls(null, $mockStatsMenu)
        ;

        $mockMenu->expects($this->once())
            ->method('addChild')
            ->with('微信小程序统计数据')
            ->willReturn($mockStatsMenu)
        ;

        // 期望会创建子菜单和子菜单项
        $mockStatsMenu->expects($this->atLeastOnce())
            ->method('getChild')
            ->willReturn(null)
        ;

        $mockStatsMenu->expects($this->atLeastOnce())
            ->method('addChild')
            ->willReturn($this->createMock(ItemInterface::class))
        ;

        $this->adminMenu->__invoke($mockMenu);
    }

    public function testInvokeWithExistingMenu(): void
    {
        $mockMenu = $this->createMock(ItemInterface::class);
        $mockStatsMenu = $this->createMock(ItemInterface::class);

        // 模拟菜单已存在的情况
        $mockMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('微信小程序统计数据')
            ->willReturn($mockStatsMenu)
        ;

        $mockMenu->expects($this->never())
            ->method('addChild')
        ;

        // 期望会创建子菜单项
        $mockStatsMenu->expects($this->atLeastOnce())
            ->method('getChild')
            ->willReturn(null)
        ;

        $mockStatsMenu->expects($this->atLeastOnce())
            ->method('addChild')
            ->willReturn($this->createMock(ItemInterface::class))
        ;

        $this->adminMenu->__invoke($mockMenu);
    }

    public function testGetMenuItemsReturnsCorrectStructure(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $this->assertNotEmpty($menuItems);

        // 验证包含必要的菜单结构
        $menuLabels = [];
        foreach ($menuItems as $item) {
            $itemDto = $item->getAsDto();
            $label = $itemDto->getLabel();
            if (is_string($label)) {
                $menuLabels[] = $label;
            }
        }

        // 验证包含主要功能模块
        $this->assertContains('微信小程序统计数据', $menuLabels);
    }

    public function testMenuItemsHaveCorrectStructure(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        foreach ($menuItems as $item) {
            // 验证每个菜单项都有有效的标签
            $itemDto = $item->getAsDto();
            $label = $itemDto->getLabel();
            $this->assertNotEmpty($label, 'Menu item should have non-empty label');

            // 验证子菜单项有正确的结构
            if ('submenu' === $itemDto->getType()) {
                $subItems = $itemDto->getSubItems();

                foreach ($subItems as $subItem) {
                    $this->assertNotEmpty($subItem->getLabel(), 'Sub item should have non-empty label');
                }
            }
        }
    }

    public function testAllExpectedSubmenusArePresent(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $submenuLabels = [];
        foreach ($menuItems as $item) {
            $itemDto = $item->getAsDto();
            if ('submenu' === $itemDto->getType()) {
                $label = $itemDto->getLabel();
                if (is_string($label)) {
                    $submenuLabels[] = $label;
                }
            }
        }

        $expectedSubmenus = [
            '访问趋势分析',
            '用户画像分析',
            '访问行为分析',
            '页面访问分析',
            '用户留存分析',
            '性能数据分析',
        ];

        foreach ($expectedSubmenus as $expectedSubmenu) {
            $this->assertContains($expectedSubmenu, $submenuLabels, "Should contain submenu: {$expectedSubmenu}");
        }
    }

    public function testMenuImplementsMenuProviderInterface(): void
    {
        $this->assertInstanceOf(
            MenuProviderInterface::class,
            $this->adminMenu
        );
    }
}
