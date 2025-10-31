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
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;

/**
 * 每小时访问数据管理
 *
 * @extends AbstractCrudController<HourVisitData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/hour-visit-data',
    routeName: 'wechat_mini_program_stats_hour_visit_data'
)]
final class HourVisitDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HourVisitData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('每小时访问数据')
            ->setEntityLabelInPlural('每小时访问数据列表')
            ->setPageTitle('index', '每小时访问数据列表')
            ->setPageTitle('new', '创建每小时访问数据')
            ->setPageTitle('edit', '编辑每小时访问数据')
            ->setPageTitle('detail', '每小时访问数据详情')
            ->setHelp('index', '管理用户按小时统计的访问数据')
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

        yield DateTimeField::new('date', '日期时间')
            ->setHelp('统计数据的日期时间')
        ;

        yield IntegerField::new('visitUserUv', '访问用户UV')
            ->setHelp('该小时的访问用户UV')
        ;

        yield IntegerField::new('visitUserPv', '访问用户PV')
            ->setHelp('该小时的访问用户PV')
        ;

        yield IntegerField::new('pagePv', '页面PV')
            ->setHelp('该小时的页面PV')
        ;

        yield IntegerField::new('newUser', '新用户数')
            ->setHelp('该小时的新用户数')
        ;

        yield IntegerField::new('visitNewUserPv', '新用户访问PV')
            ->setHelp('该小时的新用户访问PV')
        ;

        yield IntegerField::new('pageNewUserPv', '新用户页面PV')
            ->setHelp('该小时的新用户页面PV')
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
            ->add(NumericFilter::new('visitUserUv', '访问用户UV'))
            ->add(NumericFilter::new('visitUserPv', '访问用户PV'))
            ->add(NumericFilter::new('pagePv', '页面PV'))
            ->add(NumericFilter::new('newUser', '新用户数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
