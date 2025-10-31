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
use WechatMiniProgramStatsBundle\Repository\UserAccessesWeekDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: UserAccessesWeekDataRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_user_accesses_week_data', options: ['comment' => '获取用户访问小程序周留存'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_user_accesses_week_data_uniq', columns: ['date', 'account_id', 'type'])]
class UserAccessesWeekData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Length(max: 255)]
    private ?string $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '留存标记'])]
    #[Assert\Length(max: 255)]
    private ?string $retentionMark = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '类型'])]
    #[Assert\Length(max: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '用户数量'])]
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

    public function getRetentionMark(): ?string
    {
        return $this->retentionMark;
    }

    public function setRetentionMark(?string $retentionMark): void
    {
        $this->retentionMark = $retentionMark;
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

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'retentionMark' => $this->getRetentionMark(),
            'type' => $this->getType(),
            'userNumber' => $this->getUserNumber(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
