<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Tests\Service;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
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
    private LinkGeneratorInterface $linkGenerator;

    protected function onSetUp(): void
    {
        // Mock the LinkGeneratorInterface
        $this->linkGenerator = $this->createMock(LinkGeneratorInterface::class);

        // Replace the service in the container
        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);

        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testGetMenuItemsReturnsCorrectStructure(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $this->assertIsArray($menuItems);
        $this->assertNotEmpty($menuItems);

        // 验证所有菜单项都是有效的 MenuItemInterface 实例
        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
        }

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

    public function testGetMenuItemsHasCorrectStructure(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        // 验证第一个项目是区段标题
        $firstItem = $menuItems[0];
        $this->assertInstanceOf(MenuItemInterface::class, $firstItem);
        $firstItemDto = $firstItem->getAsDto();
        $this->assertSame('section', $firstItemDto->getType());
        $this->assertSame('微信小程序统计数据', $firstItemDto->getLabel());
        $this->assertSame('fas fa-chart-line', $firstItemDto->getIcon());
    }

    public function testAllExpectedSubmenusArePresent(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $submenuLabels = [];
        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
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

    public function testSubmenuItemsHaveCorrectIcons(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $labelIconMap = [];
        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
            $itemDto = $item->getAsDto();
            $label = $itemDto->getLabel();
            if (is_string($label)) {
                $labelIconMap[$label] = $itemDto->getIcon();
            }
        }

        $expectedLabelIcons = [
            '访问趋势分析' => 'fas fa-chart-area',
            '用户画像分析' => 'fas fa-users',
            '访问行为分析' => 'fas fa-mouse-pointer',
            '页面访问分析' => 'fas fa-file-alt',
            '用户留存分析' => 'fas fa-user-check',
            '性能数据分析' => 'fas fa-tachometer-alt',
        ];

        foreach ($expectedLabelIcons as $label => $expectedIcon) {
            $this->assertArrayHasKey($label, $labelIconMap);
            $this->assertSame($expectedIcon, $labelIconMap[$label]);
        }
    }

    public function testVisitTrendSubmenuHasCorrectItems(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $visitTrendMenu = null;
        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
            $itemDto = $item->getAsDto();
            if ('submenu' === $itemDto->getType() && '访问趋势分析' === $itemDto->getLabel()) {
                $visitTrendMenu = $item;
                break;
            }
        }

        $this->assertNotNull($visitTrendMenu, '访问趋势分析 submenu should be present');

        $subItems = $visitTrendMenu->getAsDto()->getSubItems();
        $this->assertCount(5, $subItems);

        $subItemLabels = array_map(fn ($item) => $item->getLabel(), $subItems);
        $expectedLabels = [
            '每日访问趋势',
            '每周访问趋势',
            '每月访问趋势',
            '小时访问分布',
            '每日汇总数据',
        ];

        foreach ($expectedLabels as $expectedLabel) {
            $this->assertContains($expectedLabel, $subItemLabels);
        }
    }

    public function testUserPortraitSubmenuHasCorrectItems(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $userPortraitMenu = null;
        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
            $itemDto = $item->getAsDto();
            if ('submenu' === $itemDto->getType() && '用户画像分析' === $itemDto->getLabel()) {
                $userPortraitMenu = $item;
                break;
            }
        }

        $this->assertNotNull($userPortraitMenu, '用户画像分析 submenu should be present');

        $subItems = $userPortraitMenu->getAsDto()->getSubItems();
        $this->assertCount(7, $subItems);

        $subItemLabels = array_map(fn ($item) => $item->getLabel(), $subItems);
        $expectedLabels = [
            '用户画像总览',
            '年龄分布',
            '性别分布',
            '地域分布-省份',
            '地域分布-城市',
            '设备分布',
            '平台分布',
        ];

        foreach ($expectedLabels as $expectedLabel) {
            $this->assertContains($expectedLabel, $subItemLabels);
        }
    }

    public function testAllMenuItemsHaveNonEmptyLabels(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        foreach ($menuItems as $item) {
            $this->assertInstanceOf(MenuItemInterface::class, $item);
            $itemDto = $item->getAsDto();
            $label = $itemDto->getLabel();
            $this->assertNotEmpty($label, 'Menu item should have non-empty label');

            // 验证子菜单项也有非空标签
            if ('submenu' === $itemDto->getType()) {
                $subItems = $itemDto->getSubItems();
                foreach ($subItems as $subItem) {
                    $this->assertNotEmpty($subItem->getLabel(), 'Sub item should have non-empty label');
                }
            }
        }
    }

    public function testMenuImplementsMenuProviderInterface(): void
    {
        $this->assertInstanceOf(
            MenuProviderInterface::class,
            $this->adminMenu
        );
    }

    public function testAdminMenuCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }
}
