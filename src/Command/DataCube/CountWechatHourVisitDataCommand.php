<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;

#[AsCronTask(expression: '46 * * * *')]
#[AsCronTask(expression: '13 * * * *')]
#[AsCommand(name: self::NAME, description: '统计小程序每小时访问情况')]
#[Autoconfigure(public: true)]
class CountWechatHourVisitDataCommand extends LockableCommand
{
    public const NAME = 'wechat-mini-program:count-wechat-hour-visit-data';
    //    public function __construct(
    //        private readonly AccountRepository $accountRepository,
    //        private readonly HourVisitDataRepository $wechatHourVisitDataRepository,
    //        private readonly EntityManagerInterface $entityManager,
    //    ) {
    //        parent::__construct();
    //    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //        $start = CarbonImmutable::now()->startOfHour();
        //        $end = CarbonImmutable::now()->endOfHour();
        //
        //        //        $start = CarbonImmutable::parse('2023-08-16 10:33:11')->startOfHour();
        //        //        $end = CarbonImmutable::parse('2023-08-16 10:33:11')->endOfHour();
        //
        //        $account = $this->accountRepository->findOneBy(['valid' => true]);
        // //        $data = $this->logRepository->createQueryBuilder('l')
        // //            ->select('count(1) as pv,count(DISTINCT l.createdBy) as uv')
        // //            ->where('l.createTime between :start and :end')
        // //            ->setParameter('start', $start->clone())
        // //            ->setParameter('end', $end->clone())
        // //            ->getQuery()
        // //            ->getScalarResult();
        // //        $newUser = $this->bizUserRepository->createQueryBuilder('u')
        // //            ->select('count(1)')
        // //            ->where('u.createTime between :start and :end')
        // //            ->setParameter('start', $start->clone())
        // //            ->setParameter('end', $end->clone())
        // //            ->getQuery()
        // //            ->getSingleScalarResult();
        //        $newUser = 0;
        //
        // //        $pageVisit = $this->pageLogRepository->createQueryBuilder('u')
        // //            ->select('count(1)')
        // //            ->where('u.createTime between :start and :end')
        // //            ->setParameter('start', $start->clone())
        // //            ->setParameter('end', $end->clone())
        // //            ->getQuery()
        // //            ->getSingleScalarResult();
        //        $pageVisit = 0;
        //
        // //        $newUserArr = $this->bizUserRepository->createQueryBuilder('u')
        // //            ->select('u.username')
        // //            ->where('u.createTime between :start and :end')
        // //            ->setParameter('start', $start->clone()->startOfDay())
        // //            ->setParameter('end', $end->clone())
        // //            ->getQuery()
        // //            ->getSingleColumnResult();
        //        $newUserArr = [];
        //
        // //        $newVisitData = $this->logRepository->createQueryBuilder('l')
        // //            ->select('count(1) as pv')
        // //            ->where('l.createTime between :start and :end and l.createdBy in (:createdBy)')
        // //            ->setParameter('start', $start->clone())
        // //            ->setParameter('end', $end->clone())
        // //            ->setParameter('createdBy', $newUserArr)
        // //            ->getQuery()
        // //            ->getSingleScalarResult();
        //
        // //        $pageNewUserVisit = $this->pageLogRepository->createQueryBuilder('u')
        // //            ->select('count(1)')
        // //            ->where('u.createTime between :start and :end and u.createdBy in (:createdBy)')
        // //            ->setParameter('start', $start->clone())
        // //            ->setParameter('end', $end->clone())
        // //            ->setParameter('createdBy', $newUserArr)
        // //            ->getQuery()
        // //            ->getSingleScalarResult();
        //        $pageNewUserVisit = 0;
        //
        //        var_dump($newUserArr, $newVisitData, $pageNewUserVisit);
        //        $entity = $this->wechatHourVisitDataRepository->findOneBy([
        //            'account' => $account,
        //            'date' => $start,
        //        ]);
        //        if ((bool) empty($entity)) {
        //            $entity = new HourVisitData();
        //            $entity->setAccount($account);
        //            $entity->setDate($start);
        //        }
        //        $entity->setVisitUserPv($data[0]['pv']);
        //        $entity->setVisitUserUv($data[0]['uv']);
        //        $entity->setNewUser($newUser);
        //        $entity->setPagePv($pageVisit);
        //        $entity->setVisitNewUserPv($newVisitData);
        //        $entity->setPageNewUserPv($pageNewUserVisit);
        //        $this->entityManager->persist($entity);
        //        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
