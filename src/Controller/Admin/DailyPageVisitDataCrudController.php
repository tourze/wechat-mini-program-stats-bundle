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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\DailyPageVisitData;

/**
 * 每日页面访问数据管理
 *
 * @extends AbstractCrudController<DailyPageVisitData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/daily-page-visit-data',
    routeName: 'wechat_mini_program_stats_daily_page_visit_data'
)]
final class DailyPageVisitDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyPageVisitData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('每日页面访问数据')
            ->setEntityLabelInPlural('每日页面访问数据列表')
            ->setPageTitle('index', '每日页面访问数据列表')
            ->setPageTitle('new', '创建每日页面访问数据')
            ->setPageTitle('edit', '编辑每日页面访问数据')
            ->setPageTitle('detail', '每日页面访问数据详情')
            ->setHelp('index', '管理小程序各页面每日的访问统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'page'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield DateField::new('date', '日期')
            ->setHelp('统计数据的日期')
        ;

        yield TextField::new('page', '页面路径')
            ->setHelp('小程序页面的路径地址')
        ;

        yield IntegerField::new('visitPv', '页面访问量')
            ->setHelp('该页面的访问次数')
        ;

        yield IntegerField::new('visitUv', '页面访问用户数')
            ->setHelp('该页面的访问用户数')
        ;

        yield IntegerField::new('newUserVisitPv', '新用户访问量')
            ->setHelp('新用户访问该页面的次数')
        ;

        yield IntegerField::new('newUserVisitUv', '新用户访问用户数')
            ->setHelp('访问该页面的新用户数')
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
            ->add(TextFilter::new('page', '页面路径'))
            ->add(NumericFilter::new('visitPv', '页面访问量'))
            ->add(NumericFilter::new('visitUv', '页面访问用户数'))
            ->add(NumericFilter::new('newUserVisitPv', '新用户访问量'))
            ->add(NumericFilter::new('newUserVisitUv', '新用户访问用户数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
