<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceRepository;

#[AsPermission(title: '微信小程序性能')]
#[Listable]
#[ORM\Entity(repositoryClass: PerformanceRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_performance', options: ['comment' => '微信小程序性能'])]
class Performance implements AdminArrayInterface
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

    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[ListColumn]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[Filterable]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 255, enumType: PerformanceModule::class, options: ['comment' => '性能指标'])]
    private ?PerformanceModule $module = null;

    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 32, options: ['comment' => '名称'])]
    private ?string $name = null;

    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '中文含义'])]
    private ?string $nameZh = null;

    #[ORM\OneToMany(mappedBy: 'performance', targetEntity: PerformanceAttribute::class)]
    private Collection $wechatPerformanceAttributes;

    public function __construct()
    {
        $this->wechatPerformanceAttributes = new ArrayCollection();
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

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'account' => $this->getAccount(),
            'module' => $this->getModule(),
            'name' => $this->getName(),
            'nameZh' => $this->getNameZh(),
            'attributes' => $this->getWechatPerformanceAttributes()->map(fn (PerformanceAttribute $attribute) => $attribute->retrieveAdminArray())->toArray(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getNameZh(): ?string
    {
        return $this->nameZh;
    }

    public function setNameZh(string $nameZh): self
    {
        $this->nameZh = $nameZh;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getModule(): ?PerformanceModule
    {
        return $this->module;
    }

    public function setModule(PerformanceModule $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return Collection<int, PerformanceAttribute>
     */
    public function getWechatPerformanceAttributes(): Collection
    {
        return $this->wechatPerformanceAttributes;
    }

    public function addWechatPerformanceAttribute(PerformanceAttribute $wechatPerformanceAttribute): static
    {
        if (!$this->wechatPerformanceAttributes->contains($wechatPerformanceAttribute)) {
            $this->wechatPerformanceAttributes->add($wechatPerformanceAttribute);
            $wechatPerformanceAttribute->setPerformance($this);
        }

        return $this;
    }

    public function removeWechatPerformanceAttribute(PerformanceAttribute $wechatPerformanceAttribute): static
    {
        if ($this->wechatPerformanceAttributes->removeElement($wechatPerformanceAttribute)) {
            // set the owning side to null (unless already changed)
            if ($wechatPerformanceAttribute->getPerformance() === $this) {
                $wechatPerformanceAttribute->setPerformance(null);
            }
        }

        return $this;
    }
}
