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
use WechatMiniProgramStatsBundle\Entity\UserPortraitCityData;

/**
 * 用户画像城市分布数据管理
 *
 * @extends AbstractCrudController<UserPortraitCityData>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/user-portrait-city-data',
    routeName: 'wechat_mini_program_stats_user_portrait_city_data'
)]
final class UserPortraitCityDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPortraitCityData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('用户画像城市数据')
            ->setEntityLabelInPlural('用户画像城市数据列表')
            ->setPageTitle('index', '用户画像城市数据列表')
            ->setPageTitle('new', '创建用户画像城市数据')
            ->setPageTitle('edit', '编辑用户画像城市数据')
            ->setPageTitle('detail', '用户画像城市数据详情')
            ->setHelp('index', '管理用户按城市分布的画像统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'name', 'type'])
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
            ->setHelp('数据分类类型')
        ;

        yield TextField::new('name', '名称')
            ->setRequired(true)
            ->setHelp('城市或地区名称')
        ;

        yield TextField::new('value', '值')
            ->setRequired(true)
            ->setHelp('统计数值')
        ;

        yield TextField::new('valueId', '值ID')
            ->setHelp('关联的值标识符')
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
            ->add(TextFilter::new('value', '值'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
