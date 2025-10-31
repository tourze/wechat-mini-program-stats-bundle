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
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;

/**
 * 用户访问页面数据管理
 *
 * @extends AbstractCrudController<UserAccessPageData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/user-access-page-data',
    routeName: 'wechat_mini_program_stats_user_access_page_data'
)]
final class UserAccessPageDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAccessPageData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('用户访问页面数据')
            ->setEntityLabelInPlural('用户访问页面数据列表')
            ->setPageTitle('index', '用户访问页面数据列表')
            ->setPageTitle('new', '创建用户访问页面数据')
            ->setPageTitle('edit', '编辑用户访问页面数据')
            ->setPageTitle('detail', '用户访问页面数据详情')
            ->setHelp('index', '管理用户访问小程序各页面的详细统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'pagePath'])
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

        yield DateField::new('date', '日期')
            ->setRequired(true)
            ->setHelp('统计数据的日期')
        ;

        yield TextField::new('pagePath', '页面路径')
            ->setRequired(true)
            ->setHelp('小程序页面的路径地址')
        ;

        yield IntegerField::new('pageVisitPv', '页面访问量')
            ->setRequired(true)
            ->setHelp('该页面的总访问次数')
        ;

        yield IntegerField::new('pageVisitUv', '页面访问用户数')
            ->setRequired(true)
            ->setHelp('访问该页面的独立用户数')
        ;

        yield IntegerField::new('pageStayTime', '页面停留时长')
            ->setHelp('用户在该页面的平均停留时间')
        ;

        yield IntegerField::new('entryPagePv', '入口页访问量')
            ->setHelp('以该页面作为入口的访问次数')
        ;

        yield IntegerField::new('exitPagePv', '退出页访问量')
            ->setHelp('以该页面作为退出页的访问次数')
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
            ->add(TextFilter::new('pagePath', '页面路径'))
            ->add(NumericFilter::new('pageVisitPv', '页面访问量'))
            ->add(NumericFilter::new('pageVisitUv', '页面访问用户数'))
            ->add(NumericFilter::new('entryPagePv', '入口页访问量'))
            ->add(NumericFilter::new('exitPagePv', '退出页访问量'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
