<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\AccessSourceVisitUvRepository;

#[ORM\Entity(repositoryClass: AccessSourceVisitUvRepository::class)]
#[ORM\Table(name: 'wechat_access_source_visit_uv_data', options: ['comment' => '获取用户小程序访问分布数据(访问来源VisitUv分布)'])]
#[ORM\UniqueConstraint(name: 'wechat_access_source_visit_uv_uniq', columns: ['date', 'account_id', 'data_key'])]
class AccessSourceVisitUv implements AdminArrayInterface
, \Stringable{
    use SnowflakeKeyAware;
    use CreateTimeAware;


    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '数据字段'])]
    private ?string $dataKey = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '数据字段'])]
    private ?string $DataValue = null;


    public function getDataValue(): ?string
    {
        return $this->DataValue;
    }

    public function setDataValue(?string $DataValue): void
    {
        $this->DataValue = $DataValue;
    }

    public function getDataKey(): ?string
    {
        return $this->dataKey;
    }

    public function setDataKey(?string $dataKey): void
    {
        $this->dataKey = $dataKey;
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
            'date' => $this->getDate(),
            'dataKey' => $this->getDataKey(),
            'dataValue' => $this->getDataValue(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
