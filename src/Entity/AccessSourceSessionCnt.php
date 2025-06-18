<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\AccessSourceSessionCntRepository;

#[Listable]
#[ORM\Entity(repositoryClass: AccessSourceSessionCntRepository::class)]
#[ORM\Table(name: 'wechat_access_source_session_cnt_data', options: ['comment' => '获取用户小程序访问分布数据(访问来源分布)'])]
#[ORM\UniqueConstraint(name: 'wechat_access_source_session_cnt_uniq', columns: ['date', 'account_id', 'data_key'])]
class AccessSourceSessionCnt implements AdminArrayInterface
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    private ?string $dataKey = null;

    private ?string $DataValue = null;

    public function getId(): ?string
    {
        return $this->id;
    }

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
}
