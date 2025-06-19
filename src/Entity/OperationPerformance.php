<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\OperationPerformanceRepository;

#[ORM\Entity(repositoryClass: OperationPerformanceRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_operation_performance', options: ['comment' => '运维中心-查询性能数据'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_operation_performance_uniq', columns: ['account_id', 'date', 'cost_time_type'])]
class OperationPerformance implements AdminArrayInterface
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

    private ?\DateTimeInterface $date = null;

    private ?string $costTimeType = null;

    private ?string $costTime = null;
public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCostTimeType(): ?string
    {
        return $this->costTimeType;
    }

    public function setCostTimeType(string $costTimeType): self
    {
        $this->costTimeType = $costTimeType;

        return $this;
    }

    public function getCostTime(): ?string
    {
        return $this->costTime;
    }

    public function setCostTime(string $costTime): self
    {
        $this->costTime = $costTime;

        return $this;
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

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'costTimeType' => $this->getCostTimeType(),
            'costTime' => $this->getCostTime(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
