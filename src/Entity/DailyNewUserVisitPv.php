<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\DailyNewUserVisitPvRepository;

#[AsPermission(title: '新用户访问小程序次数')]
#[Listable]
#[ORM\Entity(repositoryClass: DailyNewUserVisitPvRepository::class)]
#[ORM\Table(name: 'wechat_daily_new_user_visit_pv', options: ['comment' => '新用户访问小程序次数'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_new_user_visit_pv_idx_uniq', columns: ['date', 'account_id'])]
class DailyNewUserVisitPv implements AdminArrayInterface
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[Filterable]
    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false, options: ['comment' => '数据日期'])]
    private \DateTimeInterface $date;

    #[ListColumn]
    #[ORM\Column(nullable: true, options: ['comment' => 'pv'])]
    private ?int $visitPv = 0;

    #[ListColumn]
    #[ORM\Column(nullable: true, options: ['comment' => 'uv'])]
    private ?int $visitUv = 0;

    #[ListColumn]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remark = null;

    #[ORM\ManyToOne]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setCreateTime(?\DateTimeInterface $createdAt): self
    {
        $this->createTime = $createdAt;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): static
    {
        $this->remark = $remark;

        return $this;
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'visitPv' => $this->getVisitPv(),
            'visitUv' => $this->getVisitUv(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getVisitUv(): ?int
    {
        return $this->visitUv;
    }

    public function setVisitUv(int $visitUv): static
    {
        $this->visitUv = $visitUv;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getVisitPv(): ?int
    {
        return $this->visitPv;
    }

    public function setVisitPv(?int $visitPv): void
    {
        $this->visitPv = $visitPv;
    }
}
