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
use WechatMiniProgramStatsBundle\Entity\PerformanceData;

/**
 * 性能数据管理
 *
 * @extends AbstractCrudController<PerformanceData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/performance-data',
    routeName: 'wechat_mini_program_stats_performance_data'
)]
final class PerformanceDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PerformanceData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('性能数据')
            ->setEntityLabelInPlural('性能数据列表')
            ->setPageTitle('index', '性能数据列表')
            ->setPageTitle('new', '创建性能数据')
            ->setPageTitle('edit', '编辑性能数据')
            ->setPageTitle('detail', '性能数据详情')
            ->setHelp('index', '管理小程序性能监控的具体数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'description', 'module'])
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

        yield TextField::new('module', '模块')
            ->setHelp('性能监控的模块')
        ;

        yield TextField::new('description', '描述')
            ->setHelp('性能指标的描述')
        ;

        yield TextField::new('value', '指标值')
            ->setHelp('性能指标的具体数值')
        ;

        yield TextField::new('networkType', '网络类型')
            ->setHelp('网络类型')
        ;

        yield TextField::new('deviceLevel', '设备级别')
            ->setHelp('设备级别')
        ;

        yield TextField::new('metricsId', '指标ID')
            ->setHelp('指标唯一标识')
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
            ->add(TextFilter::new('module', '模块'))
            ->add(TextFilter::new('description', '描述'))
            ->add(TextFilter::new('networkType', '网络类型'))
            ->add(TextFilter::new('deviceLevel', '设备级别'))
            ->add(TextFilter::new('value', '指标值'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
