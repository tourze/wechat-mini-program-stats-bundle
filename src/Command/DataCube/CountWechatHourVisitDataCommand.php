<?php

namespace WechatMiniProgramStatsBundle\Command\DataCube;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\LockCommandBundle\Command\LockableCommand;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramStatsBundle\Entity\HourVisitData;
use WechatMiniProgramStatsBundle\Repository\HourVisitDataRepository;

#[AsCronTask('46 * * * *')]
#[AsCronTask('13 * * * *')]
#[AsCommand(name: 'wechat-mini-program:CountWechatHourVisitData', description: '统计小程序每小时访问情况')]
class CountWechatHourVisitDataCommand extends LockableCommand
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly HourVisitDataRepository $wechatHourVisitDataRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = Carbon::now()->startOfHour();
        $end = Carbon::now()->endOfHour();

        //        $start = Carbon::parse('2023-08-16 10:33:11')->startOfHour();
        //        $end = Carbon::parse('2023-08-16 10:33:11')->endOfHour();

        $account = $this->accountRepository->findOneBy(['valid' => true]);
//        $data = $this->logRepository->createQueryBuilder('l')
//            ->select('count(1) as pv,count(DISTINCT l.createdBy) as uv')
//            ->where('l.createTime between :start and :end')
//            ->setParameter('start', $start->clone())
//            ->setParameter('end', $end->clone())
//            ->getQuery()
//            ->getScalarResult();
//        $newUser = $this->bizUserRepository->createQueryBuilder('u')
//            ->select('count(1)')
//            ->where('u.createTime between :start and :end')
//            ->setParameter('start', $start->clone())
//            ->setParameter('end', $end->clone())
//            ->getQuery()
//            ->getSingleScalarResult();
        $newUser = 0;

//        $pageVisit = $this->pageLogRepository->createQueryBuilder('u')
//            ->select('count(1)')
//            ->where('u.createTime between :start and :end')
//            ->setParameter('start', $start->clone())
//            ->setParameter('end', $end->clone())
//            ->getQuery()
//            ->getSingleScalarResult();
        $pageVisit = 0;

//        $newUserArr = $this->bizUserRepository->createQueryBuilder('u')
//            ->select('u.username')
//            ->where('u.createTime between :start and :end')
//            ->setParameter('start', $start->clone()->startOfDay())
//            ->setParameter('end', $end->clone())
//            ->getQuery()
//            ->getSingleColumnResult();
        $newUserArr = [];

//        $newVisitData = $this->logRepository->createQueryBuilder('l')
//            ->select('count(1) as pv')
//            ->where('l.createTime between :start and :end and l.createdBy in (:createdBy)')
//            ->setParameter('start', $start->clone())
//            ->setParameter('end', $end->clone())
//            ->setParameter('createdBy', $newUserArr)
//            ->getQuery()
//            ->getSingleScalarResult();

//        $pageNewUserVisit = $this->pageLogRepository->createQueryBuilder('u')
//            ->select('count(1)')
//            ->where('u.createTime between :start and :end and u.createdBy in (:createdBy)')
//            ->setParameter('start', $start->clone())
//            ->setParameter('end', $end->clone())
//            ->setParameter('createdBy', $newUserArr)
//            ->getQuery()
//            ->getSingleScalarResult();
        $pageNewUserVisit = 0;

        var_dump($newUserArr, $newVisitData, $pageNewUserVisit);
        $entity = $this->wechatHourVisitDataRepository->findOneBy([
            'account' => $account,
            'date' => $start,
        ]);
        if (empty($entity)) {
            $entity = new HourVisitData();
            $entity->setAccount($account);
            $entity->setDate($start);
        }
        $entity->setVisitUserPv($data[0]['pv']);
        $entity->setVisitUserUv($data[0]['uv']);
        $entity->setNewUser($newUser);
        $entity->setPagePv($pageVisit);
        $entity->setVisitNewUserPv($newVisitData);
        $entity->setPageNewUserPv($pageNewUserVisit);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
