<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyVisitTrendDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 * @implements ApiArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DailyVisitTrendDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_visit_trend_data', options: ['comment' => '获取用户访问小程序数据日趋势'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_visit_trend_idx_uniq', columns: ['date', 'account_id'])]
class DailyVisitTrendData implements ApiArrayInterface, AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '会话次数'])]
    #[Assert\PositiveOrZero]
    private ?int $sessionCnt = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '访问次数'])]
    #[Assert\PositiveOrZero]
    private ?int $visitPv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '访问人数'])]
    #[Assert\PositiveOrZero]
    private ?int $visitUv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '新访问人数'])]
    #[Assert\PositiveOrZero]
    private ?int $visitUvNew = 0;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '人均停留时长'])]
    #[Assert\Length(max: 255)]
    private ?string $stayTimeUv = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '次均停留时长'])]
    #[Assert\Length(max: 255)]
    private ?string $stayTimeSession = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '平均访问深度'])]
    #[Assert\Length(max: 255)]
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

    /**
     * @return array<string, mixed>
     */
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
