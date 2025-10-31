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
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\WeeklyVisitTrend;

/**
 * 周度访问趋势数据管理
 *
 * @extends AbstractCrudController<WeeklyVisitTrend>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/weekly-visit-trend',
    routeName: 'wechat_mini_program_stats_weekly_visit_trend'
)]
final class WeeklyVisitTrendCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WeeklyVisitTrend::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('周度访问趋势数据')
            ->setEntityLabelInPlural('周度访问趋势数据列表')
            ->setPageTitle('index', '周度访问趋势数据列表')
            ->setPageTitle('new', '创建周度访问趋势数据')
            ->setPageTitle('edit', '编辑周度访问趋势数据')
            ->setPageTitle('detail', '周度访问趋势数据详情')
            ->setHelp('index', '管理用户访问小程序的周度趋势统计数据')
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
            ->setRequired(true)
            ->autocomplete()
        ;

        yield DateField::new('beginDate', '开始日期')
            ->setRequired(true)
            ->setHelp('统计周期的开始日期')
        ;

        yield DateField::new('endDate', '结束日期')
            ->setRequired(true)
            ->setHelp('统计周期的结束日期')
        ;

        yield TextField::new('sessionCnt', '会话次数')
            ->setHelp('当周的会话总数')
        ;

        yield TextField::new('visitPv', '访问次数')
            ->setHelp('当周的访问页面总数')
        ;

        yield TextField::new('visitUv', '访问人数')
            ->setHelp('当周的访问用户总数')
        ;

        yield TextField::new('visitUvNew', '新访问人数')
            ->setHelp('当周的新访问用户数')
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
            ->add(TextFilter::new('sessionCnt', '会话次数'))
            ->add(TextFilter::new('visitPv', '访问次数'))
            ->add(TextFilter::new('visitUv', '访问人数'))
            ->add(TextFilter::new('visitUvNew', '新访问人数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
