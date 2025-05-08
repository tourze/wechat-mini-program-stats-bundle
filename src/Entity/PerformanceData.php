<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\PerformanceDataRepository;

#[AsPermission(title: '获取小程序性能数据')]
#[Listable]
#[Creatable]
#[ORM\Entity(repositoryClass: PerformanceDataRepository::class)]
#[ORM\Table(name: 'wechat_performance_data', options: ['comment' => '获取小程序性能数据'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id', 'description'])]
class PerformanceData implements AdminArrayInterface
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => 'module编码'])]
    private ?string $module = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '网络类型'])]
    private ?string $networkType = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '机型'])]
    private ?string $deviceLevel = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '性能数据指标id'])]
    private ?string $metricsId = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '说明'])]
    private ?string $description = null;

    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false, options: ['comment' => '数据日期'])]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '值'])]
    private ?string $value = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setCreateTime(?\DateTimeInterface $createdAt): self
    {
        $this->createTime = $createdAt;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

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
}
