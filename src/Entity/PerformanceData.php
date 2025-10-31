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
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: PerformanceDataRepository::class)]
#[ORM\Table(name: 'wechat_performance_data', options: ['comment' => '获取小程序性能数据'])]
#[ORM\UniqueConstraint(name: 'wechat_performance_data_idx_uniq', columns: ['date', 'account_id', 'description'])]
class PerformanceData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '模块'])]
    #[Assert\Length(max: 255)]
    private ?string $module = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '网络类型'])]
    #[Assert\Length(max: 255)]
    private ?string $networkType = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '设备级别'])]
    #[Assert\Length(max: 255)]
    private ?string $deviceLevel = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '指标ID'])]
    #[Assert\Length(max: 255)]
    private ?string $metricsId = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '描述'])]
    #[Assert\Length(max: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '值'])]
    #[Assert\Length(max: 255)]
    private ?string $value = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getMetricsId(): ?string
    {
        return $this->metricsId;
    }

    public function setMetricsId(?string $metricsId): void
    {
        $this->metricsId = $metricsId;
    }

    public function getDeviceLevel(): ?string
    {
        return $this->deviceLevel;
    }

    public function setDeviceLevel(?string $deviceLevel): void
    {
        $this->deviceLevel = $deviceLevel;
    }

    public function getNetworkType(): ?string
    {
        return $this->networkType;
    }

    public function setNetworkType(?string $networkType): void
    {
        $this->networkType = $networkType;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(?string $module): void
    {
        $this->module = $module;
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
            'module' => $this->getModule(),
            'networkType' => $this->getNetworkType(),
            'deviceLevel' => $this->getDeviceLevel(),
            'metricsId' => $this->getMetricsId(),
            'description' => $this->getDescription(),
            'date' => $this->getDate(),
            'value' => $this->getValue(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
