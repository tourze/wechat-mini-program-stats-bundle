<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Service;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Menu\MenuItemInterface;
use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramStatsBundle\Controller\Admin\AccessDepthInfoDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\AccessSourceSessionCntCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\AccessSourceVisitUvCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\AccessStayTimeInfoDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\DailyNewUserVisitPvCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\DailyPageVisitDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\DailyRetainDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\DailySummaryDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\DailyVisitTrendDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\HourVisitDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\MonthlyVisitTrendCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\OperationPerformanceCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\PerformanceAttributeCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\PerformanceCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\PerformanceDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserAccessesMonthDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserAccessesWeekDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserAccessPageDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitAgeDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitCityDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitDeviceDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitGendersDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitPlatformDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\UserPortraitProvinceDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\VisitDistributionDataCrudController;
use WechatMiniProgramStatsBundle\Controller\Admin\WeeklyVisitTrendCrudController;

/**
 * 微信小程序统计数据管理菜单
 */
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('微信小程序统计数据')) {
            $item->addChild('微信小程序统计数据');
        }

        $statsMenu = $item->getChild('微信小程序统计数据');
        if (null !== $statsMenu) {
            $this->addVisitTrendSubmenu($statsMenu);
            $this->addUserPortraitSubmenu($statsMenu);
            $this->addAccessBehaviorSubmenu($statsMenu);
            $this->addPageAccessSubmenu($statsMenu);
            $this->addUserRetentionSubmenu($statsMenu);
            $this->addPerformanceSubmenu($statsMenu);
        }
    }

    private function addVisitTrendSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('访问趋势分析')) {
            $parent->addChild('访问趋势分析');
        }

        $submenu = $parent->getChild('访问趋势分析');
        if (null !== $submenu) {
            $submenu->addChild('每日访问趋势')
                ->setUri($this->linkGenerator->getCurdListPage(DailyVisitTrendDataCrudController::class))
            ;

            $submenu->addChild('每周访问趋势')
                ->setUri($this->linkGenerator->getCurdListPage(WeeklyVisitTrendCrudController::class))
            ;

            $submenu->addChild('每月访问趋势')
                ->setUri($this->linkGenerator->getCurdListPage(MonthlyVisitTrendCrudController::class))
            ;

            $submenu->addChild('小时访问分布')
                ->setUri($this->linkGenerator->getCurdListPage(HourVisitDataCrudController::class))
            ;

            $submenu->addChild('每日汇总数据')
                ->setUri($this->linkGenerator->getCurdListPage(DailySummaryDataCrudController::class))
            ;
        }
    }

    private function addUserPortraitSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('用户画像分析')) {
            $parent->addChild('用户画像分析');
        }

        $submenu = $parent->getChild('用户画像分析');
        if (null !== $submenu) {
            $submenu->addChild('用户画像总览')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitDataCrudController::class))
            ;

            $submenu->addChild('年龄分布')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitAgeDataCrudController::class))
            ;

            $submenu->addChild('性别分布')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitGendersDataCrudController::class))
            ;

            $submenu->addChild('地域分布-省份')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitProvinceDataCrudController::class))
            ;

            $submenu->addChild('地域分布-城市')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitCityDataCrudController::class))
            ;

            $submenu->addChild('设备分布')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitDeviceDataCrudController::class))
            ;

            $submenu->addChild('平台分布')
                ->setUri($this->linkGenerator->getCurdListPage(UserPortraitPlatformDataCrudController::class))
            ;
        }
    }

    private function addAccessBehaviorSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('访问行为分析')) {
            $parent->addChild('访问行为分析');
        }

        $submenu = $parent->getChild('访问行为分析');
        if (null !== $submenu) {
            $submenu->addChild('访问深度分布')
                ->setUri($this->linkGenerator->getCurdListPage(AccessDepthInfoDataCrudController::class))
            ;

            $submenu->addChild('访问时长分布')
                ->setUri($this->linkGenerator->getCurdListPage(AccessStayTimeInfoDataCrudController::class))
            ;

            $submenu->addChild('访问来源-会话')
                ->setUri($this->linkGenerator->getCurdListPage(AccessSourceSessionCntCrudController::class))
            ;

            $submenu->addChild('访问来源-用户')
                ->setUri($this->linkGenerator->getCurdListPage(AccessSourceVisitUvCrudController::class))
            ;

            $submenu->addChild('访问分布数据')
                ->setUri($this->linkGenerator->getCurdListPage(VisitDistributionDataCrudController::class))
            ;
        }
    }

    private function addPageAccessSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('页面访问分析')) {
            $parent->addChild('页面访问分析');
        }

        $submenu = $parent->getChild('页面访问分析');
        if (null !== $submenu) {
            $submenu->addChild('每日页面访问')
                ->setUri($this->linkGenerator->getCurdListPage(DailyPageVisitDataCrudController::class))
            ;

            $submenu->addChild('用户页面访问')
                ->setUri($this->linkGenerator->getCurdListPage(UserAccessPageDataCrudController::class))
            ;
        }
    }

    private function addUserRetentionSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('用户留存分析')) {
            $parent->addChild('用户留存分析');
        }

        $submenu = $parent->getChild('用户留存分析');
        if (null !== $submenu) {
            $submenu->addChild('每日留存数据')
                ->setUri($this->linkGenerator->getCurdListPage(DailyRetainDataCrudController::class))
            ;

            $submenu->addChild('新用户访问数据')
                ->setUri($this->linkGenerator->getCurdListPage(DailyNewUserVisitPvCrudController::class))
            ;

            $submenu->addChild('用户周访问数据')
                ->setUri($this->linkGenerator->getCurdListPage(UserAccessesWeekDataCrudController::class))
            ;

            $submenu->addChild('用户月访问数据')
                ->setUri($this->linkGenerator->getCurdListPage(UserAccessesMonthDataCrudController::class))
            ;
        }
    }

    private function addPerformanceSubmenu(ItemInterface $parent): void
    {
        if (null === $parent->getChild('性能数据分析')) {
            $parent->addChild('性能数据分析');
        }

        $submenu = $parent->getChild('性能数据分析');
        if (null !== $submenu) {
            $submenu->addChild('性能模块配置')
                ->setUri($this->linkGenerator->getCurdListPage(PerformanceCrudController::class))
            ;

            $submenu->addChild('性能属性配置')
                ->setUri($this->linkGenerator->getCurdListPage(PerformanceAttributeCrudController::class))
            ;

            $submenu->addChild('性能数据记录')
                ->setUri($this->linkGenerator->getCurdListPage(PerformanceDataCrudController::class))
            ;

            $submenu->addChild('运营性能数据')
                ->setUri($this->linkGenerator->getCurdListPage(OperationPerformanceCrudController::class))
            ;
        }
    }

    /**
     * @return array<MenuItemInterface>
     */
    public function getMenuItems(): array
    {
        return [
            MenuItem::section('微信小程序统计数据', 'fas fa-chart-line'),

            // 访问趋势分析
            MenuItem::subMenu('访问趋势分析', 'fas fa-chart-area')
                ->setSubItems([
                    MenuItem::linkToCrud('每日访问趋势', 'fas fa-calendar-day', DailyVisitTrendDataCrudController::class),
                    MenuItem::linkToCrud('每周访问趋势', 'fas fa-calendar-week', WeeklyVisitTrendCrudController::class),
                    MenuItem::linkToCrud('每月访问趋势', 'fas fa-calendar-alt', MonthlyVisitTrendCrudController::class),
                    MenuItem::linkToCrud('小时访问分布', 'fas fa-clock', HourVisitDataCrudController::class),
                    MenuItem::linkToCrud('每日汇总数据', 'fas fa-chart-pie', DailySummaryDataCrudController::class),
                ]),

            // 用户画像分析
            MenuItem::subMenu('用户画像分析', 'fas fa-users')
                ->setSubItems([
                    MenuItem::linkToCrud('用户画像总览', 'fas fa-user-circle', UserPortraitDataCrudController::class),
                    MenuItem::linkToCrud('年龄分布', 'fas fa-birthday-cake', UserPortraitAgeDataCrudController::class),
                    MenuItem::linkToCrud('性别分布', 'fas fa-venus-mars', UserPortraitGendersDataCrudController::class),
                    MenuItem::linkToCrud('地域分布-省份', 'fas fa-map-marked-alt', UserPortraitProvinceDataCrudController::class),
                    MenuItem::linkToCrud('地域分布-城市', 'fas fa-city', UserPortraitCityDataCrudController::class),
                    MenuItem::linkToCrud('设备分布', 'fas fa-mobile-alt', UserPortraitDeviceDataCrudController::class),
                    MenuItem::linkToCrud('平台分布', 'fas fa-desktop', UserPortraitPlatformDataCrudController::class),
                ]),

            // 访问行为分析
            MenuItem::subMenu('访问行为分析', 'fas fa-mouse-pointer')
                ->setSubItems([
                    MenuItem::linkToCrud('访问深度分布', 'fas fa-layer-group', AccessDepthInfoDataCrudController::class),
                    MenuItem::linkToCrud('访问时长分布', 'fas fa-hourglass-half', AccessStayTimeInfoDataCrudController::class),
                    MenuItem::linkToCrud('访问来源-会话', 'fas fa-external-link-alt', AccessSourceSessionCntCrudController::class),
                    MenuItem::linkToCrud('访问来源-用户', 'fas fa-user-friends', AccessSourceVisitUvCrudController::class),
                    MenuItem::linkToCrud('访问分布数据', 'fas fa-chart-bar', VisitDistributionDataCrudController::class),
                ]),

            // 页面访问分析
            MenuItem::subMenu('页面访问分析', 'fas fa-file-alt')
                ->setSubItems([
                    MenuItem::linkToCrud('每日页面访问', 'fas fa-calendar-check', DailyPageVisitDataCrudController::class),
                    MenuItem::linkToCrud('用户页面访问', 'fas fa-user-clock', UserAccessPageDataCrudController::class),
                ]),

            // 用户留存分析
            MenuItem::subMenu('用户留存分析', 'fas fa-user-check')
                ->setSubItems([
                    MenuItem::linkToCrud('每日留存数据', 'fas fa-chart-line', DailyRetainDataCrudController::class),
                    MenuItem::linkToCrud('新用户访问数据', 'fas fa-user-plus', DailyNewUserVisitPvCrudController::class),
                    MenuItem::linkToCrud('用户周访问数据', 'fas fa-calendar-week', UserAccessesWeekDataCrudController::class),
                    MenuItem::linkToCrud('用户月访问数据', 'fas fa-calendar', UserAccessesMonthDataCrudController::class),
                ]),

            // 性能数据分析
            MenuItem::subMenu('性能数据分析', 'fas fa-tachometer-alt')
                ->setSubItems([
                    MenuItem::linkToCrud('性能模块配置', 'fas fa-cogs', PerformanceCrudController::class),
                    MenuItem::linkToCrud('性能属性配置', 'fas fa-sliders-h', PerformanceAttributeCrudController::class),
                    MenuItem::linkToCrud('性能数据记录', 'fas fa-chart-line', PerformanceDataCrudController::class),
                    MenuItem::linkToCrud('运营性能数据', 'fas fa-business-time', OperationPerformanceCrudController::class),
                ]),
        ];
    }
}
