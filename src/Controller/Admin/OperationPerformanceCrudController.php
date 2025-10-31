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
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\OperationPerformance;

/**
 * 运营性能数据管理
 *
 * @extends AbstractCrudController<OperationPerformance>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/operation-performance',
    routeName: 'wechat_mini_program_stats_operation_performance'
)]
final class OperationPerformanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OperationPerformance::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('运营性能数据')
            ->setEntityLabelInPlural('运营性能数据列表')
            ->setPageTitle('index', '运营性能数据列表')
            ->setPageTitle('new', '创建运营性能数据')
            ->setPageTitle('edit', '编辑运营性能数据')
            ->setPageTitle('detail', '运营性能数据详情')
            ->setHelp('index', '管理小程序运营性能的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'module', 'metricName'])
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

        yield TextField::new('costTimeType', '耗时类型')
            ->setHelp('性能数据的耗时类型')
        ;

        yield TextField::new('costTime', '耗时')
            ->setHelp('性能指标的耗时值')
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
            ->add(DateTimeFilter::new('date', '日期'))
            ->add(TextFilter::new('costTimeType', '耗时类型'))
            ->add(TextFilter::new('costTime', '耗时'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
