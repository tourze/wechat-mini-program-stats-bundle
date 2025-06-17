<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Action\Exportable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

#[AsPermission(title: '每日页面请求汇总日志')]
#[Exportable]
#[ORM\Entity(repositoryClass: DailyPageVisitDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_page_visit_data', options: ['comment' => '每日页面请求汇总日志'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_page_visit_data_idx_uniq', columns: ['date', 'page'])]
class DailyPageVisitData implements AdminArrayInterface
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['comment' => '日期'])]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column(length: 255, options: ['comment' => '页面路径'])]
    private string $page;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '请求pv'])]
    private int $visitPv;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '请求Uv'])]
    private ?int $visitUv;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '新用户请求pv'])]
    private ?int $newUserVisitPv;

    #[ListColumn]
    #[ORM\Column(options: ['comment' => '新用户请求Uv'])]
    private ?int $newUserVisitUv;

    use TimestampableAware;

    public function getPage(): string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getVisitPv(): int
    {
        return $this->visitPv;
    }

    public function setVisitPv(int $visitPv): self
    {
        $this->visitPv = $visitPv;

        return $this;
    }

    public function getVisitUv(): ?int
    {
        return $this->visitUv;
    }

    public function setVisitUv(?int $visitUv): void
    {
        $this->visitUv = $visitUv;
    }

    public function getNewUserVisitPv(): ?int
    {
        return $this->newUserVisitPv;
    }

    public function setNewUserVisitPv(?int $newUserVisitPv): void
    {
        $this->newUserVisitPv = $newUserVisitPv;
    }

    public function getNewUserVisitUv(): ?int
    {
        return $this->newUserVisitUv;
    }

    public function setNewUserVisitUv(?int $newUserVisitUv): void
    {
        $this->newUserVisitUv = $newUserVisitUv;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'page' => $this->getPage(),
            'visitPv' => $this->getVisitPv(),
            'visitUv' => $this->getVisitUv(),
            'newUserVisitPv' => $this->getNewUserVisitPv(),
            'newUserVisitUv' => $this->getNewUserVisitUv(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ];
    }
}
