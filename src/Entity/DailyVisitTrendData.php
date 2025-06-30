<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

#[ORM\Entity(repositoryClass: DailyVisitTrendDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_visit_trend_data', options: ['comment' => '获取用户访问小程序数据日趋势'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id'])]
class DailyVisitTrendData implements ApiArrayInterface, AdminArrayInterface
, \Stringable{
    use SnowflakeKeyAware;
    use CreateTimeAware;


    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

    private ?int $sessionCnt = 0;

    private ?int $visitPv = 0;

    private ?int $visitUv = 0;

    private ?int $visitUvNew = 0;

    private ?string $stayTimeUv = null;

    private ?string $stayTimeSession = null;

    private ?string $visitDepth = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;


    public function getVisitUvNew(): ?int
    {
        return $this->visitUvNew;
    }

    public function setVisitUvNew(?int $visitUvNew): void
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

    public function getVisitUv(): ?int
    {
        return $this->visitUv;
    }

    public function setVisitUv(?int $visitUv): void
    {
        $this->visitUv = $visitUv;
    }

    public function getVisitPv(): ?int
    {
        return $this->visitPv;
    }

    public function setVisitPv(?int $visitPv): void
    {
        $this->visitPv = $visitPv;
    }

    public function getSessionCnt(): ?int
    {
        return $this->sessionCnt;
    }

    public function setSessionCnt(?int $sessionCnt): void
    {
        $this->sessionCnt = $sessionCnt;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
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

    public function retrieveApiArray(): array
    {
        return [
            'sessionCnt' => $this->getSessionCnt(),
            'visitPv' => $this->getVisitPv(),
            'visitUv' => $this->getVisitUv(),
            'visitUvNew' => $this->getVisitUvNew(),
            'date' => $this->getDate(),
        ];
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
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
