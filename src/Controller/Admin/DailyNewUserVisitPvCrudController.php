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
use WechatMiniProgramStatsBundle\Entity\DailyNewUserVisitPv;

/**
 * 新用户每日访问数据管理
 *
 * @extends AbstractCrudController<DailyNewUserVisitPv>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/daily-new-user-visit-pv',
    routeName: 'wechat_mini_program_stats_daily_new_user_visit_pv'
)]
final class DailyNewUserVisitPvCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyNewUserVisitPv::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('新用户访问数据')
            ->setEntityLabelInPlural('新用户访问数据列表')
            ->setPageTitle('index', '新用户访问数据列表')
            ->setPageTitle('new', '创建新用户访问数据')
            ->setPageTitle('edit', '编辑新用户访问数据')
            ->setPageTitle('detail', '新用户访问数据详情')
            ->setHelp('index', '管理新用户每日访问的统计数据')
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

        yield IntegerField::new('visitPv', '访问次数')
            ->setRequired(false)
            ->setHelp('新用户的访问页面数')
        ;

        yield IntegerField::new('visitUv', '访问人数')
            ->setHelp('新用户的访问人数')
        ;

        yield TextField::new('remark', '备注')
            ->setRequired(false)
            ->setHelp('备注信息')
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
            ->add(NumericFilter::new('visitPv', '访问次数'))
            ->add(NumericFilter::new('visitUv', '访问人数'))
            ->add(TextFilter::new('remark', '备注'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
