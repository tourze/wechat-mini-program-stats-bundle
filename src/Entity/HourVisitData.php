<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\HourVisitDataRepository;

#[AsPermission(title: '小程序每小时访问数据')]
#[Listable]
#[ORM\Entity(repositoryClass: HourVisitDataRepository::class)]
#[ORM\Table(name: 'wechat_hour_visit_data', options: ['comment' => '小程序每小时访问数据'])]
#[ORM\UniqueConstraint(name: 'wechat_hour_visit_data_idx_uniq', columns: ['date', 'account_id'])]
class HourVisitData implements AdminArrayInterface
{
    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[Filterable]
    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::DATETIME_MUTABLE, nullable: false, options: ['comment' => '日期，YmdH'])]
    private \DateTimeInterface $date;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '访问人数'])]
    private ?int $visitUserUv = 0;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '打开次数'])]
    private ?int $visitUserPv = 0;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '访问页面数'])]
    private ?int $pagePv = 0;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '新增用户'])]
    private ?int $newUser = 0;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '新用户打开次数'])]
    private ?int $visitNewUserPv = 0;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '新用户访问页面数'])]
    private ?int $pageNewUserPv = 0;

    #[ListColumn]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getVisitUserUv(): ?string
    {
        return $this->visitUserUv;
    }

    public function setVisitUserUv(?string $visitUserUv): void
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
}
