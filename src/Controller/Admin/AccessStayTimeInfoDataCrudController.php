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
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;

/**
 * 用户小程序访问分布数据(访问时长的分布)管理
 *
 * @extends AbstractCrudController<AccessStayTimeInfoData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/access-stay-time-info-data',
    routeName: 'wechat_mini_program_stats_access_stay_time_info_data'
)]
final class AccessStayTimeInfoDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccessStayTimeInfoData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('访问时长分布数据')
            ->setEntityLabelInPlural('访问时长分布数据列表')
            ->setPageTitle('index', '访问时长分布数据列表')
            ->setPageTitle('new', '创建访问时长分布数据')
            ->setPageTitle('edit', '编辑访问时长分布数据')
            ->setPageTitle('detail', '访问时长分布数据详情')
            ->setHelp('index', '管理用户小程序访问时长分布的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'dataKey', 'dataValue'])
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
            ->setHelp('访问时长的分类字段')
        ;

        yield TextField::new('dataValue', '数据值')
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
