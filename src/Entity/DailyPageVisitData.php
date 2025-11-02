<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DailyPageVisitDataRepository::class)]
#[ORM\Table(name: 'wechat_daily_page_visit_data', options: ['comment' => '每日页面请求汇总日志'])]
#[ORM\UniqueConstraint(name: 'wechat_daily_page_visit_data_idx_uniq', columns: ['date', 'page'])]
class DailyPageVisitData implements AdminArrayInterface, \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 500, options: ['comment' => '页面路径'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 500)]
    private string $page;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '访问PV'])]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $visitPv;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '访问UV'])]
    #[Assert\PositiveOrZero]
    private ?int $visitUv;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '新用户访问PV'])]
    #[Assert\PositiveOrZero]
    private ?int $newUserVisitPv;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '新用户访问UV'])]
    #[Assert\PositiveOrZero]
    private ?int $newUserVisitUv;

    public function getPage(): string
    {
        return $this->page;
    }

    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    public function getVisitPv(): int
    {
        return $this->visitPv;
    }

    public function setVisitPv(int $visitPv): void
    {
        $this->visitPv = $visitPv;
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array<string, mixed>
     */
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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
