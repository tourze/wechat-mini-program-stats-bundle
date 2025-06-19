<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;

#[ORM\Entity(repositoryClass: UserPortraitAgeDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_portrait_age_data', options: ['comment' => '用户画像分布age(类型)数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_portrait_age_data_uniq', columns: ['date', 'type', 'account_id', 'name'])]
class UserPortraitAgeData implements AdminArrayInterface
, \Stringable{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    private ?string $date = null;

    private ?string $type = null;

    private ?string $name = null;

    private ?string $value = null;

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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
