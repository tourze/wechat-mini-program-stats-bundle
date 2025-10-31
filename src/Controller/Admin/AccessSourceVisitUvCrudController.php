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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;

/**
 * 访问来源用户数据管理
 *
 * @extends AbstractCrudController<AccessSourceVisitUv>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/access-source-visit-uv',
    routeName: 'wechat_mini_program_stats_access_source_visit_uv'
)]
final class AccessSourceVisitUvCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccessSourceVisitUv::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('访问来源用户数据')
            ->setEntityLabelInPlural('访问来源用户数据列表')
            ->setPageTitle('index', '访问来源用户数据列表')
            ->setPageTitle('new', '创建访问来源用户数据')
            ->setPageTitle('edit', '编辑访问来源用户数据')
            ->setPageTitle('detail', '访问来源用户数据详情')
            ->setHelp('index', '管理用户访问来源的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'dataKey'])
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

        yield TextField::new('dataKey', '数据字段')
            ->setRequired(false)
            ->setHelp('访问来源的数据分类字段')
        ;

        yield TextField::new('dataValue', '数据值')
            ->setRequired(false)
            ->setHelp('对应分类的统计数值')
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
            ->add(TextFilter::new('dataKey', '数据字段'))
            ->add(TextFilter::new('dataValue', '数据值'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
