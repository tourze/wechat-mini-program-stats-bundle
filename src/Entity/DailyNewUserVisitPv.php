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
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DailyNewUserVisitPvRepository::class)]
#[ORM\Table(name: 'wechat_daily_new_user_visit_pv', options: ['comment' => '新用户访问小程序次数'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_new_user_visit_pv_idx_uniq', columns: ['date', 'account_id'])]
class DailyNewUserVisitPv implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, options: ['comment' => '日期'])]
    #[Assert\NotNull]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '访问次数'])]
    #[Assert\PositiveOrZero]
    private ?int $visitPv = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '访问人数', 'default' => 0])]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $visitUv = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '备注'])]
    #[Assert\Length(max: 65535)]
    private ?string $remark = null;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    public function setCreateTime(?\DateTimeImmutable $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'visitPv' => $this->getVisitPv(),
            'visitUv' => $this->getVisitUv(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getVisitUv(): ?int
    {
        return $this->visitUv;
    }

    public function setVisitUv(int $visitUv): void
    {
        $this->visitUv = $visitUv;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getVisitPv(): ?int
    {
        return $this->visitPv;
    }

    public function setVisitPv(?int $visitPv): void
    {
        $this->visitPv = $visitPv;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
