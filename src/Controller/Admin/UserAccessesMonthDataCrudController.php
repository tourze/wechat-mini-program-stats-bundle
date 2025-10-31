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
use WechatMiniProgramStatsBundle\Entity\UserAccessesMonthData;

/**
 * 用户月访问数据管理
 *
 * @extends AbstractCrudController<UserAccessesMonthData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/user-accesses-month-data',
    routeName: 'wechat_mini_program_stats_user_accesses_month_data'
)]
final class UserAccessesMonthDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAccessesMonthData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('用户月访问数据')
            ->setEntityLabelInPlural('用户月访问数据列表')
            ->setPageTitle('index', '用户月访问数据列表')
            ->setPageTitle('new', '创建用户月访问数据')
            ->setPageTitle('edit', '编辑用户月访问数据')
            ->setPageTitle('detail', '用户月访问数据详情')
            ->setHelp('index', '管理用户每月访问行为的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'type'])
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

        yield TextField::new('date', '月份')
            ->setHelp('统计数据的月份')
        ;

        yield TextField::new('type', '指标类型')
            ->setHelp('访问数据的指标类型')
        ;

        yield TextField::new('retentionMark', '留存标记')
            ->setHelp('留存数据的标记信息')
        ;

        yield TextField::new('userNumber', '用户数量')
            ->setHelp('对应类型的用户数量')
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
            ->add(TextFilter::new('type', '指标类型'))
            ->add(TextFilter::new('retentionMark', '留存标记'))
            ->add(TextFilter::new('userNumber', '用户数量'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
