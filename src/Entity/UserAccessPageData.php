<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

#[AsPermission(title: '用户访问页面数据')]
#[Listable]
#[Creatable]
#[ORM\Entity(repositoryClass: UserAccessPageDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_page_data', options: ['comment' => '用户访问页面数据'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_retain_idx_uniq', columns: ['date', 'page_path', 'account_id'])]
class UserAccessPageData implements AdminArrayInterface
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
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

    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: true, options: ['comment' => '数据日期'])]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '页面路径'])]
    private ?string $pagePath;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '访问次数'])]
    private ?int $pageVisitPv = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '访问人数'])]
    private ?int $pageVisitUv = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '次均停留时长'])]
    private ?float $pageStayTime = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '进入页面次数'])]
    private ?int $entryPagePv = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '退出页面次数'])]
    private ?int $exitPagePv = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '转发次数'])]
    private ?int $pageSharePv = 0;

    #[ListColumn]
    #[ORM\Column(length: 11, options: ['comment' => '转发人数'])]
    private ?int $pageShareUv = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
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

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setPageShareUv(?int $pageShareUv): void
    {
        $this->pageShareUv = $pageShareUv;
    }

    public function getPageShareUv(): ?int
    {
        return $this->pageShareUv;
    }

    public function setPageSharePv(?int $pageSharePv): void
    {
        $this->pageSharePv = $pageSharePv;
    }

    public function getPageSharePv(): ?int
    {
        return $this->pageSharePv;
    }

    public function setExitPagePv(?int $exitPagePv): void
    {
        $this->exitPagePv = $exitPagePv;
    }

    public function getExitPagePv(): ?int
    {
        return $this->exitPagePv;
    }

    public function setEntryPagePv(?int $entryPagePv): void
    {
        $this->entryPagePv = $entryPagePv;
    }

    public function getEntryPagePv(): ?int
    {
        return $this->entryPagePv;
    }

    public function setPageStayTime(?float $pageStayTime): void
    {
        $this->pageStayTime = $pageStayTime;
    }

    public function getPageStayTime(): ?float
    {
        return $this->pageStayTime;
    }

    public function setPageVisitUv(?int $pageVisitUv): void
    {
        $this->pageVisitUv = $pageVisitUv;
    }

    public function getPageVisitUv(): ?int
    {
        return $this->pageVisitUv;
    }

    public function getPagePath(): ?string
    {
        return $this->pagePath;
    }

    public function setPagePath(?string $pagePath): void
    {
        $this->pagePath = $pagePath;
    }

    public function setPageVisitPv(?int $pageVisitPv): void
    {
        $this->pageVisitPv = $pageVisitPv;
    }

    public function getPageVisitPv(): ?int
    {
        return $this->pageVisitPv;
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
            'page' => $this->getPagePath(),
            'pageVisitPv' => $this->getPageVisitPv(),
            'pageVisitUv' => $this->getPageVisitUv(),
            'pageStayTimePv' => $this->getPageStayTime(),
            'entryPagePv' => $this->getEntryPagePv(),
            'exitPagePv' => $this->getExitPagePv(),
            'pageSharePv' => $this->getPageSharePv(),
            'pageShareUv' => $this->getPageShareUv(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }
}
