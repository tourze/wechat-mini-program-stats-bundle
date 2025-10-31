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
use WechatMiniProgramStatsBundle\Entity\DailyRetainData;

/**
 * 每日留存数据管理
 *
 * @extends AbstractCrudController<DailyRetainData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/daily-retain-data',
    routeName: 'wechat_mini_program_stats_daily_retain_data'
)]
final class DailyRetainDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyRetainData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('每日留存数据')
            ->setEntityLabelInPlural('每日留存数据列表')
            ->setPageTitle('index', '每日留存数据列表')
            ->setPageTitle('new', '创建每日留存数据')
            ->setPageTitle('edit', '编辑每日留存数据')
            ->setPageTitle('detail', '每日留存数据详情')
            ->setHelp('index', '管理用户在小程序中的留存率统计数据')
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

        yield TextField::new('type', '留存类型')
            ->setRequired(false)
            ->setHelp('留存数据的类型标识')
        ;

        yield TextField::new('userNumber', '用户数')
            ->setRequired(false)
            ->setHelp('留存用户数量')
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
            ->add(TextFilter::new('type', '留存类型'))
            ->add(TextFilter::new('userNumber', '用户数'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
