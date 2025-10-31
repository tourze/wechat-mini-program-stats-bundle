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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

/**
 * 性能属性数据管理
 *
 * @extends AbstractCrudController<PerformanceAttribute>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/performance-attribute',
    routeName: 'wechat_mini_program_stats_performance_attribute'
)]
final class PerformanceAttributeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PerformanceAttribute::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('性能属性')
            ->setEntityLabelInPlural('性能属性列表')
            ->setPageTitle('index', '性能属性列表')
            ->setPageTitle('new', '创建性能属性')
            ->setPageTitle('edit', '编辑性能属性')
            ->setPageTitle('detail', '性能属性详情')
            ->setHelp('index', '管理小程序性能指标的属性信息')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'name', 'nameZh', 'unit'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield AssociationField::new('performance', '所属性能模块')
            ->setRequired(true)
            ->autocomplete()
        ;

        yield TextField::new('name', '属性名称')
            ->setRequired(true)
            ->setHelp('性能属性的名称')
        ;

        yield TextField::new('value', '属性值')
            ->setRequired(true)
            ->setHelp('性能属性的值')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('performance', '所属性能模块'))
            ->add(TextFilter::new('name', '属性名称'))
            ->add(TextFilter::new('value', '属性值'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
