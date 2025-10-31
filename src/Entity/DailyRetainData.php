<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyRetainDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DailyRetainDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_retain_data', options: ['comment' => '获取用户访问小程序日留存'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'account_id', 'type'])]
class DailyRetainData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, options: ['comment' => '日期'])]
    #[Assert\NotNull]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '类型'])]
    #[Assert\Length(max: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '用户数'])]
    #[Assert\Length(max: 255)]
    private ?string $userNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

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

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getDate(): \DateTimeImmutable
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

    /**
     * @return array<string, mixed>
     */
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
