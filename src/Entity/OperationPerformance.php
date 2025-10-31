<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\OperationPerformanceRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: OperationPerformanceRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_operation_performance', options: ['comment' => '运维中心-查询性能数据'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_operation_performance_uniq', columns: ['account_id', 'date', 'cost_time_type'])]
class OperationPerformance implements AdminArrayInterface, \Stringable
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

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '耗时类型'])]
    #[Assert\Length(max: 255)]
    private ?string $costTimeType = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '耗时'])]
    #[Assert\Length(max: 255)]
    private ?string $costTime = null;

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getCostTimeType(): ?string
    {
        return $this->costTimeType;
    }

    public function setCostTimeType(string $costTimeType): void
    {
        $this->costTimeType = $costTimeType;
    }

    public function getCostTime(): ?string
    {
        return $this->costTime;
    }

    public function setCostTime(string $costTime): void
    {
        $this->costTime = $costTime;
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
