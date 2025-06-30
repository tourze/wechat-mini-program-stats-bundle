<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\CarbonImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\DoctrineUpsertBundle\Service\UpsertManager;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\UserAccessPageData;
use WechatMiniProgramStatsBundle\Request\DataCube\GetVisitPageRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getVisitPage.html
 */
#[AsCronTask(expression: '2 4 * * *')]
#[AsCronTask(expression: '38 23 * * *')]
#[AsCommand(name: self::NAME, description: '获取用户访问页面数据')]
class GetWechatUserAccessPageDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:get-wechat-user-access-page-data';
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly UpsertManager $upsertManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = CarbonImmutable::now();
        $dates = [
            $now->subDay(),
            $now->subDays(2),
            $now->subDays(3),
            $now->subDays(4),
            $now->subDays(5),
            $now->subDays(6),
            $now->subDays(7),
        ];

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            foreach ($dates as $date) {
                $request = new GetVisitPageRequest();
                $request->setAccount($account);
                $request->setBeginDate($date);
                $request->setEndDate($date);

                try {
                    $res = $this->client->request($request);
                } catch (\Throwable $exception) {
                    $this->logger->error('获取用户访问页面数据时发生异常', [
                        'account' => $account,
                        'exception' => $exception,
                    ]);
                    continue;
                }

                // 入库
                foreach ($res['list'] as $value) {
                    $log = new UserAccessPageData();
                    $log->setDate(CarbonImmutable::parse($res['ref_date']));
                    $log->setAccount($account);
                    $log->setPagePath($value['page_path']);
                    $log->setPageVisitPv($value['page_visit_pv']);
                    $log->setPageVisitUv($value['page_visit_uv']);
                    $log->setPageStayTime($value['page_staytime_pv']);
                    $log->setEntryPagePv($value['entrypage_pv']);
                    $log->setExitPagePv($value['exitpage_pv']);
                    $log->setPageSharePv($value['page_share_pv']);
                    $log->setPageShareUv($value['page_share_uv']);
                    $this->upsertManager->upsert($log);
                }
            }
        }

        return Command::SUCCESS;
    }
}
