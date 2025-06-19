<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;

#[ORM\Entity(repositoryClass: DailyRetainDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_retain_data', options: ['comment' => '获取用户访问小程序日留存'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id', 'type'])]
class DailyRetainData implements AdminArrayInterface
, \Stringable{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    private \DateTimeInterface $date;

    private ?string $type = null;

    private ?string $userNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getUserNumber(): ?string
    {
        return $this->userNumber;
    }

    public function setUserNumber(?string $userNumber): void
    {
        $this->userNumber = $userNumber;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): \DateTimeInterface
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
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'type' => $this->getType(),
            'userNumber' => $this->getUserNumber(),
            'date' => $this->getDate(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
