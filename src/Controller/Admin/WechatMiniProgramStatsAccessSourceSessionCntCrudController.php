<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;

class WechatMiniProgramStatsAccessSourceSessionCntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccessSourceSessionCnt::class;
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
