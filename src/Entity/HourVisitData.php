<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\HourVisitDataRepository;

#[ORM\Entity(repositoryClass: HourVisitDataRepository::class)]
#[ORM\Table(name: 'wechat_hour_visit_data', options: ['comment' => '小程序每小时访问数据'])]
#[ORM\UniqueConstraint(name: 'wechat_hour_visit_data_idx_uniq', columns: ['date', 'account_id'])]
class HourVisitData implements AdminArrayInterface
, \Stringable{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    private \DateTimeInterface $date;

    private ?int $visitUserUv = 0;

    private ?int $visitUserPv = 0;

    private ?int $pagePv = 0;

    private ?int $newUser = 0;

    private ?int $visitNewUserPv = 0;

    private ?int $pageNewUserPv = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getVisitUserUv(): ?int
    {
        return $this->visitUserUv;
    }

    public function setVisitUserUv(?int $visitUserUv): void
    {
        $this->visitUserUv = $visitUserUv;
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
            'visitUserUv' => $this->getVisitUserUv(),
            'date' => $this->getDate(),
            'visitUserPv' => $this->getVisitUserPv(),
            'pagePv' => $this->getPagePv(),
            'newUser' => $this->getNewUser(),
            'visitNewUserPv' => $this->getVisitNewUserPv(),
            'pageNewUserPv' => $this->getPageNewUserPv(),
        ];
    }

    public function getVisitUserPv(): ?int
    {
        return $this->visitUserPv;
    }

    public function setVisitUserPv(?int $visitUserPv): void
    {
        $this->visitUserPv = $visitUserPv;
    }

    public function getPagePv(): ?int
    {
        return $this->pagePv;
    }

    public function setPagePv(?int $pagePv): void
    {
        $this->pagePv = $pagePv;
    }

    public function getNewUser(): ?int
    {
        return $this->newUser;
    }

    public function setNewUser(?int $newUser): void
    {
        $this->newUser = $newUser;
    }

    public function getVisitNewUserPv(): ?int
    {
        return $this->visitNewUserPv;
    }

    public function setVisitNewUserPv(?int $visitNewUserPv): void
    {
        $this->visitNewUserPv = $visitNewUserPv;
    }

    public function getPageNewUserPv(): ?int
    {
        return $this->pageNewUserPv;
    }

    public function setPageNewUserPv(?int $pageNewUserPv): void
    {
        $this->pageNewUserPv = $pageNewUserPv;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
