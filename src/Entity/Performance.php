<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceRepository;

#[ORM\Entity(repositoryClass: PerformanceRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_performance', options: ['comment' => '微信小程序性能'])]
class Performance implements AdminArrayInterface
, \Stringable{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    private ?PerformanceModule $module = null;

    private ?string $name = null;

    private ?string $nameZh = null;

    #[ORM\OneToMany(mappedBy: 'performance', targetEntity: PerformanceAttribute::class)]
    private Collection $wechatPerformanceAttributes;

    public function __construct()
    {
        $this->wechatPerformanceAttributes = new ArrayCollection();
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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
