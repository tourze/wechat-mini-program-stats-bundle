<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\UserAccessesWeekData;

class WechatMiniProgramStatsUserAccessesWeekDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAccessesWeekData::class;
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
