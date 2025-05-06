<?php

namespace WechatMiniProgramStatsBundle\Command;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\AccessDepthInfoData;
use WechatMiniProgramStatsBundle\Entity\AccessSourceSessionCnt;
use WechatMiniProgramStatsBundle\Entity\AccessSourceVisitUv;
use WechatMiniProgramStatsBundle\Entity\AccessStayTimeInfoData;
use WechatMiniProgramStatsBundle\Repository\AccessDepthInfoDataRepository;
use WechatMiniProgramStatsBundle\Repository\AccessSourceSessionCntRepository;
use WechatMiniProgramStatsBundle\Repository\AccessSourceVisitUvRepository;
use WechatMiniProgramStatsBundle\Repository\AccessStayTimeInfoDataRepository;
use WechatMiniProgramStatsBundle\Request\DataCube\GetVisitDistriButionRequest;

/**
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/data-analysis/others/getVisitDistribution.html
 */
#[AsCronTask('34 21 * * *')]
#[AsCronTask('38 22 * * *')]
#[AsCommand(name: 'wechat-mini-program:GetVisitDistributionCommand', description: '获取小程序访问分布数据')]
class GetVisitDistributionCommand extends LockableCommand
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccessSourceSessionCntRepository $wechatAccessSourceSessionCntRepository,
        private readonly AccessStayTimeInfoDataRepository $wechatAccessStaytimeInfoDataRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AccessDepthInfoDataRepository $wechatAccessDepthInfoDataRepository,
        private readonly AccessSourceVisitUvRepository $wechatAccessSourceVisitUvRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetVisitDistriButionRequest();
            $request->setAccount($account);
            $request->setBeginDate(Carbon::now()->subDays());
            $request->setEndDate(Carbon::now()->subDays());

            try {
                $res = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error('获取小程序访问分布数据时发生异常', [
                    'account' => $account,
                    'exception' => $exception,
                ]);
                continue;
            }
            foreach ($res['list'] as $list) {
                switch ($list['index']) {
                    case 'access_staytime_info':
                        foreach ($list['item_list'] as $value) {
                            $data = $this->wechatAccessStaytimeInfoDataRepository->findOneBy([
                                'account' => $account,
                                'date' => Carbon::parse($res['ref_date']),
                                'dataKey' => $value['key'],
                            ]);
                            if (!$data) {
                                $data = new AccessStayTimeInfoData();
                                $data->setDate(Carbon::parse($res['ref_date']));
                                $data->setAccount($account);
                                $data->setDataKey($value['key']);
                            }
                            $data->setDataValue($value['value']);
                            $this->entityManager->persist($data);
                            $this->entityManager->flush();
                            $this->entityManager->detach($data);
                        }
                        break;
                    case 'access_source_visit_uv':
                        foreach ($list['item_list'] as $value) {
                            $data = $this->wechatAccessSourceVisitUvRepository->findOneBy([
                                'account' => $account,
                                'date' => Carbon::parse($res['ref_date']),
                                'dataKey' => $value['key'],
                            ]);
                            if (!$data) {
                                $data = new AccessSourceVisitUv();
                                $data->setDate(Carbon::parse($res['ref_date']));
                                $data->setAccount($account);
                                $data->setDataKey($value['key']);
                            }
                            $data->setDataValue($value['value']);
                            $this->entityManager->persist($data);
                            $this->entityManager->flush();
                            $this->entityManager->detach($data);
                        }
                        break;
                    case 'access_depth_info':
                        foreach ($list['item_list'] as $value) {
                            $data = $this->wechatAccessDepthInfoDataRepository->findOneBy([
                                'account' => $account,
                                'date' => Carbon::parse($res['ref_date']),
                                'dataKey' => $value['key'],
                            ]);
                            if (!$data) {
                                $data = new AccessDepthInfoData();
                                $data->setDate(Carbon::parse($res['ref_date']));
                                $data->setAccount($account);
                                $data->setDataKey($value['key']);
                            }
                            $data->setDataValue($value['value']);
                            $this->entityManager->persist($data);
                            $this->entityManager->flush();
                            $this->entityManager->detach($data);
                        }
                        break;
                    case 'access_source_session_cnt':
                        foreach ($list['item_list'] as $value) {
                            $data = $this->wechatAccessSourceSessionCntRepository->findOneBy([
                                'account' => $account,
                                'date' => Carbon::parse($res['ref_date']),
                                'dataKey' => $value['key'],
                            ]);
                            if (!$data) {
                                $data = new AccessSourceSessionCnt();
                                $data->setDate(Carbon::parse($res['ref_date']));
                                $data->setAccount($account);
                                $data->setDataKey($value['key']);
                            }
                            $data->setDataValue($value['value']);
                            $this->entityManager->persist($data);
                            $this->entityManager->flush();
                            $this->entityManager->detach($data);
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return Command::SUCCESS;
    }
}
