<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;

#[ORM\Entity(repositoryClass: PerformanceDataRepository::class)]
#[ORM\Table(name: 'wechat_performance_data', options: ['comment' => '获取小程序性能数据'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id', 'description'])]
class PerformanceData implements AdminArrayInterface
, \Stringable{
    use SnowflakeKeyAware;
    use CreateTimeAware;


    private ?string $module = null;

    private ?string $networkType = null;

    private ?string $deviceLevel = null;

    private ?string $metricsId = null;

    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

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

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
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

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

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
