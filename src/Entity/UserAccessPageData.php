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
use WechatMiniProgramStatsBundle\Repository\UserAccessPageDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: UserAccessPageDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_page_data', options: ['comment' => '用户访问页面数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_page_idx_uniq', columns: ['date', 'page_path', 'account_id'])]
class UserAccessPageData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 500, nullable: true, options: ['comment' => '页面路径'])]
    #[Assert\Length(max: 500)]
    private ?string $pagePath;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '页面访问PV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $pageVisitPv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '页面访问UV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $pageVisitUv = 0;

    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '页面停留时间', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?float $pageStayTime = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '入口页面PV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $entryPagePv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '出口页面PV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $exitPagePv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '页面分享PV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $pageSharePv = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '页面分享UV', 'default' => 0])]
    #[Assert\PositiveOrZero]
    private ?int $pageShareUv = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?\DateTimeImmutable
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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
