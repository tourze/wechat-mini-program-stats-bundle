<?php

namespace WechatMiniProgramStatsBundle\Controller\Admin;

use Tourze\EasyAdminExtraBundle\Controller\AbstractCrudController;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;

class WechatMiniProgramStatsAccessStayTimeInfoDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccessStayTimeInfoData::class;
    }
}
