<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[Listable]
#[ORM\Entity(repositoryClass: DailyNewUserVisitPvRepository::class)]
#[ORM\Table(name: 'wechat_daily_new_user_visit_pv', options: ['comment' => '新用户访问小程序次数'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_new_user_visit_pv_idx_uniq', columns: ['date', 'account_id'])]
class DailyNewUserVisitPv implements AdminArrayInterface
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    private \DateTimeInterface $date;

    private ?int $visitPv = 0;

    private int $visitUv = 0;

    private ?string $remark = null;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): static
    {
        $this->remark = $remark;

        return $this;
    }

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

    public function setVisitUv(int $visitUv): static
    {
        $this->visitUv = $visitUv;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getVisitPv(): ?int
    {
        return $this->visitPv;
    }

    public function setVisitPv(?int $visitPv): void
    {
        $this->visitPv = $visitPv;
    }
}
