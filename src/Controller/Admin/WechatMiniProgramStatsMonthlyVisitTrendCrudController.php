<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\MonthlyVisitTrend;

class WechatMiniProgramStatsMonthlyVisitTrendCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MonthlyVisitTrend::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
