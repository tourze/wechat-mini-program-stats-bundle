<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\PerformanceAttribute;

class WechatMiniProgramStatsPerformanceAttributeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PerformanceAttribute::class;
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
