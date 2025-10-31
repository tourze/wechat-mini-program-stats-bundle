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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use WechatMiniProgramStatsBundle\Entity\Performance;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;

/**
 * 微信小程序性能数据管理
 *
 * @extends AbstractCrudController<Performance>
 */
#[AdminCrud(
    routePath: '/wechat-mini-program-stats/performance',
    routeName: 'wechat_mini_program_stats_performance'
)]
final class PerformanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Performance::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('性能数据')
            ->setEntityLabelInPlural('性能数据列表')
            ->setPageTitle('index', '性能数据列表')
            ->setPageTitle('new', '创建性能数据')
            ->setPageTitle('edit', '编辑性能数据')
            ->setPageTitle('detail', '性能数据详情')
            ->setHelp('index', '管理微信小程序性能相关的统计数据')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['id', 'name', 'nameZh'])
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

        yield ChoiceField::new('module', '性能模块')
            ->setFormType(EnumType::class)
            ->setFormTypeOptions(['class' => PerformanceModule::class])
            ->formatValue(function ($value) {
                return $value instanceof PerformanceModule ? $value->getLabel() : '';
            })
            ->renderAsBadges()
            ->setRequired(true)
            ->setHelp('性能数据的模块类型')
        ;

        yield TextField::new('name', '英文名称')
            ->setRequired(true)
            ->setHelp('性能指标的英文名称')
        ;

        yield TextField::new('nameZh', '中文名称')
            ->setRequired(true)
            ->setHelp('性能指标的中文名称')
        ;

        yield AssociationField::new('wechatPerformanceAttributes', '性能属性')
            ->onlyOnDetail()
            ->formatValue(function ($value, $entity) {
                if (!$entity instanceof Performance) {
                    return '无';
                }
                $attributes = $entity->getWechatPerformanceAttributes();
                if (0 === $attributes->count()) {
                    return '无';
                }
                $attrNames = [];
                foreach ($attributes as $attr) {
                    if (null !== $attr && method_exists($attr, 'getName')) {
                        $attrNames[] = $attr->getName();
                    }
                }

                return implode(', ', $attrNames);
            })
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        // 性能模块选项
        $moduleChoices = [];
        foreach (PerformanceModule::cases() as $case) {
            $moduleChoices[$case->getLabel()] = $case->value;
        }

        return $filters
            ->add(EntityFilter::new('account', '小程序账号'))
            ->add(ChoiceFilter::new('module', '性能模块')->setChoices($moduleChoices))
            ->add(TextFilter::new('name', '英文名称'))
            ->add(TextFilter::new('nameZh', '中文名称'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
