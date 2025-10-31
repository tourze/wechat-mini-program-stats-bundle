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
use WechatMiniProgramStatsBundle\Entity\UserPortraitData;

/**
 * 用户画像分布数据管理
 *
 * @extends AbstractCrudController<UserPortraitData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/user-portrait-data',
    routeName: 'wechat_mini_program_stats_user_portrait_data'
)]
final class UserPortraitDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPortraitData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('用户画像数据')
            ->setEntityLabelInPlural('用户画像数据列表')
            ->setPageTitle('index', '用户画像数据列表')
            ->setPageTitle('new', '创建用户画像数据')
            ->setPageTitle('edit', '编辑用户画像数据')
            ->setPageTitle('detail', '用户画像数据详情')
            ->setHelp('index', '管理用户画像分布的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'type', 'name', 'value'])
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

        yield TextField::new('date', '日期')
            ->setRequired(true)
            ->setHelp('统计数据的日期')
        ;

        yield TextField::new('type', '类型')
            ->setRequired(true)
            ->setHelp('用户画像的数据类型')
        ;

        yield TextField::new('name', '名称')
            ->setRequired(true)
            ->setHelp('数据项名称')
        ;

        yield TextField::new('value', '数值')
            ->setRequired(true)
            ->setHelp('对应的统计数值')
        ;

        yield TextField::new('userType', '用户类型')
            ->setHelp('用户分类类型')
        ;

        yield TextField::new('province', '省份')
            ->setHelp('省份信息')
        ;

        yield DateTimeField::new('beginTime', '开始时间')
            ->setHelp('统计时间段的开始时间')
        ;

        yield DateTimeField::new('endTime', '结束时间')
            ->setHelp('统计时间段的结束时间')
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
            ->add(TextFilter::new('date', '日期'))
            ->add(TextFilter::new('type', '类型'))
            ->add(TextFilter::new('name', '名称'))
            ->add(TextFilter::new('userType', '用户类型'))
            ->add(TextFilter::new('province', '省份'))
            ->add(DateTimeFilter::new('beginTime', '开始时间'))
            ->add(DateTimeFilter::new('endTime', '结束时间'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
