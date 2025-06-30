<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailySummaryDataRepository;

#[ORM\Entity(repositoryClass: DailySummaryDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_summary_data', options: ['comment' => '获取用户小程序访问分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id'])]
class DailySummaryData implements AdminArrayInterface
, \Stringable{
    use SnowflakeKeyAware;
    use CreateTimeAware;


    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

    private ?string $visitTotal = null;

    private ?string $sharePv = null;

    private ?string $shareUv = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;


    public function getShareUv(): ?string
    {
        return $this->shareUv;
    }

    public function setShareUv(?string $shareUv): void
    {
        $this->shareUv = $shareUv;
    }

    public function getSharePv(): ?string
    {
        return $this->sharePv;
    }

    public function setSharePv(?string $sharePv): void
    {
        $this->sharePv = $sharePv;
    }

    public function getVisitTotal(): ?string
    {
        return $this->visitTotal;
    }

    public function setVisitTotal(?string $visitTotal): void
    {
        $this->visitTotal = $visitTotal;
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

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'visitTotal' => $this->getVisitTotal(),
            'sharePv' => $this->getSharePv(),
            'shareUv' => $this->getShareUv(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
