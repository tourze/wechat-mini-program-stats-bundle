<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\UserAccessesMonthDataRepository;

#[Listable]
#[ORM\Entity(repositoryClass: UserAccessesMonthDataRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_user_accesses_month_data', options: ['comment' => '获取用户访问小程序月留存'])]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_user_accesses_month_data_uniq', columns: ['date', 'account_id', 'type'])]
class UserAccessesMonthData implements AdminArrayInterface
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    private ?string $date = null;

    private ?string $retentionMark = null;

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

    public function getRetentionMark(): ?string
    {
        return $this->retentionMark;
    }

    public function setRetentionMark(?string $retentionMark): void
    {
        $this->retentionMark = $retentionMark;
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
            'retentionMark' => $this->getRetentionMark(),
            'type' => $this->getType(),
            'userNumber' => $this->getUserNumber(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }
}
