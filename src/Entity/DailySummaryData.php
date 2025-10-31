<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailySummaryDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DailySummaryDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_summary_data', options: ['comment' => '获取用户小程序访问分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_summary_idx_uniq', columns: ['date', 'account_id'])]
class DailySummaryData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '访问总数'])]
    #[Assert\Length(max: 255)]
    private ?string $visitTotal = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '分享PV'])]
    #[Assert\Length(max: 255)]
    private ?string $sharePv = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '分享UV'])]
    #[Assert\Length(max: 255)]
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

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    /**
     * @return array<string, mixed>
     */
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
