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
use WechatMiniProgramStatsBundle\Entity\DailySummaryData;

/**
 * 每日汇总数据管理
 *
 * @extends AbstractCrudController<DailySummaryData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/daily-summary-data',
    routeName: 'wechat_mini_program_stats_daily_summary_data'
)]
final class DailySummaryDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailySummaryData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('每日汇总数据')
            ->setEntityLabelInPlural('每日汇总数据列表')
            ->setPageTitle('index', '每日汇总数据列表')
            ->setPageTitle('new', '创建每日汇总数据')
            ->setPageTitle('edit', '编辑每日汇总数据')
            ->setPageTitle('detail', '每日汇总数据详情')
            ->setHelp('index', '管理小程序每日的汇总统计数据')
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

        yield TextField::new('visitTotal', '总访问量')
            ->setHelp('当日的总访问量')
        ;

        yield TextField::new('sharePv', '分享次数')
            ->setHelp('当日的分享页面数')
        ;

        yield TextField::new('shareUv', '分享人数')
            ->setHelp('当日的分享用户数')
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
            ->add(TextFilter::new('visitTotal', '总访问量'))
            ->add(TextFilter::new('sharePv', '分享次数'))
            ->add(TextFilter::new('shareUv', '分享人数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
