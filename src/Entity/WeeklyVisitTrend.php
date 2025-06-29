<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\WeeklyVisitTrendRepository;

#[ORM\Entity(repositoryClass: WeeklyVisitTrendRepository::class)]
#[ORM\Table(name: 'ims_wechat_mini_program_weekly_visit_trend', options: ['comment' => '用户访问小程序数据周趋势'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_weekly_visit_trend_idx_uniq', columns: ['account_id', 'begin_date', 'end_date'])]
class WeeklyVisitTrend implements AdminArrayInterface
, \Stringable{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    /**
     * 为周一日期
     */
    private \DateTimeInterface $beginDate;

    /**
     * 为周日日期
     */
    private \DateTimeInterface $endDate;

    private ?string $sessionCnt = null;

    private ?string $visitPv = null;

    private ?string $visitUv = null;

    private ?string $visitUvNew = null;

    private ?string $stayTimeUv = null;

    private ?string $stayTimeSession = null;

    private ?string $visitDepth = null;
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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
