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
use WechatMiniProgramStatsBundle\Interface\UserPortraitEntityInterface;
use WechatMiniProgramStatsBundle\Repository\UserPortraitCityDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: UserPortraitCityDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_portrait_city_data', options: ['comment' => '用户画像分布city(类型)数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_portrait_city_data_uniq', columns: ['date', 'type', 'account_id', 'name'])]
class UserPortraitCityData implements AdminArrayInterface, UserPortraitEntityInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Length(max: 255)]
    private ?string $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '类型'])]
    #[Assert\Length(max: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '名称'])]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '值'])]
    #[Assert\Length(max: 255)]
    private ?string $value = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '值 ID'])]
    #[Assert\Length(max: 255)]
    private ?string $valueId = null;

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

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function setCreateTime(?\DateTimeImmutable $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return array<string, mixed>
     */
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
