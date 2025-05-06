<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use Tourze\EasyAdminExtraBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\UserPortraitGendersData;

class WechatMiniProgramStatsUserPortraitGendersDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPortraitGendersData::class;
    }
}
