<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDeviceDataRepository;

#[AsPermission(title: '用户画像分布device(类型)数据')]
#[Listable]
#[Creatable]
#[ORM\Entity(repositoryClass: UserPortraitDeviceDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_portrait_device_data', options: ['comment' => '用户画像分布device(类型)数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_portrait_device_data_uniq', columns: ['date', 'type', 'account_id', 'name'])]
class UserPortraitDeviceData implements AdminArrayInterface
{
    use CreateTimeAware;

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::STRING, nullable: true, options: ['comment' => '数据日期'])]
    private ?string $date = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '数据类型(新用户、活跃用户)'])]
    private ?string $type = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '名称'])]
    private ?string $name = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '值'])]
    private ?string $value = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '值id'])]
    private ?string $valueId = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValueId(): ?string
    {
        return $this->valueId;
    }

    public function setValueId(?string $valueId): void
    {
        $this->valueId = $valueId;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?string
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
            'type' => $this->getType(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'valueId' => $this->getValueId(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }
}
