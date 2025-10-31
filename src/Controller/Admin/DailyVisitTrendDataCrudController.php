<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use WechatMiniProgramStatsBundle\Entity\DailyVisitTrendData;

/**
 * 每日访问趋势数据管理
 *
 * @extends AbstractCrudController<DailyVisitTrendData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/daily-visit-trend-data',
    routeName: 'wechat_mini_program_stats_daily_visit_trend_data'
)]
final class DailyVisitTrendDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyVisitTrendData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('每日访问趋势数据')
            ->setEntityLabelInPlural('每日访问趋势数据列表')
            ->setPageTitle('index', '每日访问趋势数据列表')
            ->setPageTitle('new', '创建每日访问趋势数据')
            ->setPageTitle('edit', '编辑每日访问趋势数据')
            ->setPageTitle('detail', '每日访问趋势数据详情')
            ->setHelp('index', '管理用户访问小程序的每日趋势统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield AssociationField::new('account', '小程序账号')
            ->autocomplete()
        ;

        yield DateField::new('date', '日期')
            ->setHelp('统计数据的日期')
        ;

        yield IntegerField::new('sessionCnt', '会话次数')
            ->setHelp('当日的会话总数')
        ;

        yield IntegerField::new('visitPv', '访问次数')
            ->setHelp('当日的访问页面总数')
        ;

        yield IntegerField::new('visitUv', '访问人数')
            ->setHelp('当日的访问用户总数')
        ;

        yield IntegerField::new('visitUvNew', '新访问人数')
            ->setHelp('当日的新访问用户数')
        ;

        yield TextField::new('stayTimeUv', '人均停留时长')
            ->setHelp('用户平均停留时间')
        ;

        yield TextField::new('stayTimeSession', '次均停留时长')
            ->setHelp('次均停留时长')
        ;

        yield TextField::new('visitDepth', '平均访问深度')
            ->setHelp('用户平均访问深度')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('account', '小程序账号'))
            ->add(NumericFilter::new('sessionCnt', '会话次数'))
            ->add(NumericFilter::new('visitPv', '访问次数'))
            ->add(NumericFilter::new('visitUv', '访问人数'))
            ->add(NumericFilter::new('visitUvNew', '新访问人数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
