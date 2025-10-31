<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\WeeklyVisitTrendRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: WeeklyVisitTrendRepository::class)]
#[ORM\Table(name: 'ims_wechat_mini_program_weekly_visit_trend', options: ['comment' => '用户访问小程序数据周趋势'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_weekly_visit_trend_idx_uniq', columns: ['account_id', 'begin_date', 'end_date'])]
class WeeklyVisitTrend implements AdminArrayInterface, \Stringable
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, options: ['comment' => '周开始日期'])]
    #[Assert\NotNull]
    private \DateTimeImmutable $beginDate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, options: ['comment' => '周结束日期'])]
    #[Assert\NotNull]
    private \DateTimeImmutable $endDate;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '访问次数'])]
    #[Assert\Length(max: 255)]
    private ?string $sessionCnt = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '访问人数'])]
    #[Assert\Length(max: 255)]
    private ?string $visitPv = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '访问人数UV'])]
    #[Assert\Length(max: 255)]
    private ?string $visitUv = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '新访问用户数'])]
    #[Assert\Length(max: 255)]
    private ?string $visitUvNew = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '人均停留时长'])]
    #[Assert\Length(max: 255)]
    private ?string $stayTimeUv = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '次均停留时长'])]
    #[Assert\Length(max: 255)]
    private ?string $stayTimeSession = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '平均访问深度'])]
    #[Assert\Length(max: 255)]
    private ?string $visitDepth = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getBeginDate(): \DateTimeImmutable
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeImmutable $beginDate): void
    {
        $this->beginDate = $beginDate;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
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

    /**
     * @return array<string, mixed>
     */
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
