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
use WechatMiniProgramStatsBundle\Entity\VisitDistributionData;

/**
 * 访问分布数据管理
 *
 * @extends AbstractCrudController<VisitDistributionData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/visit-distribution-data',
    routeName: 'wechat_mini_program_stats_visit_distribution_data'
)]
final class VisitDistributionDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VisitDistributionData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('访问分布数据')
            ->setEntityLabelInPlural('访问分布数据列表')
            ->setPageTitle('index', '访问分布数据列表')
            ->setPageTitle('new', '创建访问分布数据')
            ->setPageTitle('edit', '编辑访问分布数据')
            ->setPageTitle('detail', '访问分布数据详情')
            ->setHelp('index', '管理用户访问小程序的分布统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'type', 'sceneId'])
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

        yield TextField::new('type', '类型')
            ->setRequired(false)
            ->setHelp('访问分布的数据类型')
        ;

        yield TextField::new('sceneId', '场景ID')
            ->setRequired(false)
            ->setHelp('访问的场景标识')
        ;

        yield TextField::new('sceneIdPv', '场景ID PV')
            ->setRequired(false)
            ->setHelp('场景的页面访问量')
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
            ->add(TextFilter::new('type', '类型'))
            ->add(TextFilter::new('sceneId', '场景ID'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
