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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramStatsBundle\Entity\UserPortraitAgeData;

/**
 * 用户画像年龄分布数据管理
 *
 * @extends AbstractCrudController<UserPortraitAgeData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/user-portrait-age-data',
    routeName: 'wechat_mini_program_stats_user_portrait_age_data'
)]
final class UserPortraitAgeDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPortraitAgeData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('用户画像年龄数据')
            ->setEntityLabelInPlural('用户画像年龄数据列表')
            ->setPageTitle('index', '用户画像年龄数据列表')
            ->setPageTitle('new', '创建用户画像年龄数据')
            ->setPageTitle('edit', '编辑用户画像年龄数据')
            ->setPageTitle('detail', '用户画像年龄数据详情')
            ->setHelp('index', '管理用户按年龄分布的画像统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'type', 'name'])
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
            ->setHelp('用户画像数据类型')
        ;

        yield TextField::new('name', '年龄段')
            ->setRequired(true)
            ->setHelp('用户年龄段分类')
        ;

        yield TextField::new('value', '用户数量')
            ->setRequired(true)
            ->setHelp('该年龄段的用户数量统计')
        ;

        yield TextField::new('valueId', '值 ID')
            ->setHelp('数据值的唯一标识符')
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
            ->add(TextFilter::new('name', '年龄段'))
            ->add(TextFilter::new('value', '用户数量'))
            ->add(TextFilter::new('valueId', '值 ID'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
