<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\WeeklyVisitTrendRepository;

#[AsPermission(title: '用户访问小程序数据周趋势')]
#[ORM\Entity(repositoryClass: WeeklyVisitTrendRepository::class)]
#[ORM\Table(name: 'ims_wechat_mini_program_weekly_visit_trend', options: ['comment' => '用户访问小程序数据周趋势'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_weekly_visit_trend_idx_uniq', columns: ['account_id', 'begin_date', 'end_date'])]
class WeeklyVisitTrend implements AdminArrayInterface
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[Groups(['restful_read', 'api_tree', 'admin_curd', 'api_list'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    /**
     * 为周一日期
     */
    #[ListColumn]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ['comment' => '开始日期'])]
    private \DateTimeInterface $beginDate;

    /**
     * 为周日日期
     */
    #[ListColumn]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ['comment' => '结束日期'])]
    private \DateTimeInterface $endDate;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '打开次数（自然周内汇总）'])]
    private ?string $sessionCnt = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '访问次数（自然周内汇总）'])]
    private ?string $visitPv = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '访问人数（自然周内去重）'])]
    private ?string $visitUv = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '新用户数（自然周内去重）'])]
    private ?string $visitUvNew = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '人均停留时长 (浮点型，单位：秒)'])]
    private ?string $stayTimeUv = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '次均停留时长 (浮点型，单位：秒)'])]
    private ?string $stayTimeSession = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '平均访问深度 (浮点型)'])]
    private ?string $visitDepth = null;

    public function setCreateTime(?\DateTimeInterface $createdAt): self
    {
        $this->createTime = $createdAt;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getBeginDate(): \DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getVisitUvNew(): ?string
    {
        return $this->visitUvNew;
    }

    public function setVisitUvNew(?string $visitUvNew): void
    {
        $this->visitUvNew = $visitUvNew;
    }

    public function getVisitDepth(): ?string
    {
        return $this->visitDepth;
    }

    public function setVisitDepth(?string $visitDepth): void
    {
        $this->visitDepth = $visitDepth;
    }

    public function getStayTimeSession(): ?string
    {
        return $this->stayTimeSession;
    }

    public function setStayTimeSession(?string $stayTimeSession): void
    {
        $this->stayTimeSession = $stayTimeSession;
    }

    public function getStayTimeUv(): ?string
    {
        return $this->stayTimeUv;
    }

    public function setStayTimeUv(?string $stayTimeUv): void
    {
        $this->stayTimeUv = $stayTimeUv;
    }

    public function getVisitUv(): ?string
    {
        return $this->visitUv;
    }

    public function setVisitUv(?string $visitUv): void
    {
        $this->visitUv = $visitUv;
    }

    public function getVisitPv(): ?string
    {
        return $this->visitPv;
    }

    public function setVisitPv(?string $visitPv): void
    {
        $this->visitPv = $visitPv;
    }

    public function getSessionCnt(): ?string
    {
        return $this->sessionCnt;
    }

    public function setSessionCnt(?string $sessionCnt): void
    {
        $this->sessionCnt = $sessionCnt;
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'beginDate' => $this->getBeginDate(),
            'endDate' => $this->getEndDate(),
            'sessionCnt' => $this->getSessionCnt(),
            'visitPv' => $this->getVisitPv(),
            'visitUv' => $this->getVisitUv(),
            'visitUvNew' => $this->getVisitUvNew(),
            'stayTimeUv' => $this->getStayTimeUv(),
            'stayTimeSession' => $this->getStayTimeSession(),
            'visitDepth' => $this->getVisitDepth(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }
}
