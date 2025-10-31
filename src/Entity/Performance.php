<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Enum\PerformanceModule;
use WechatMiniProgramStatsBundle\Repository\PerformanceRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: PerformanceRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_performance', options: ['comment' => '微信小程序性能'])]
class Performance implements AdminArrayInterface, \Stringable
{
    use CreateTimeAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, enumType: PerformanceModule::class, options: ['comment' => '模块'])]
    #[Assert\Choice(callback: [PerformanceModule::class, 'cases'])]
    private ?PerformanceModule $module = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '名称'])]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '中文名称'])]
    #[Assert\Length(max: 255)]
    private ?string $nameZh = null;

    /**
     * @var Collection<int, PerformanceAttribute>
     */
    #[ORM\OneToMany(mappedBy: 'performance', targetEntity: PerformanceAttribute::class)]
    private Collection $wechatPerformanceAttributes;

    public function __construct()
    {
        $this->wechatPerformanceAttributes = new ArrayCollection();
    }

    /**
     * @return array<string, mixed>
     */
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

    public function setNameZh(string $nameZh): void
    {
        $this->nameZh = $nameZh;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getModule(): ?PerformanceModule
    {
        return $this->module;
    }

    public function setModule(PerformanceModule $module): void
    {
        $this->module = $module;
    }

    /**
     * @return Collection<int, PerformanceAttribute>
     */
    public function getWechatPerformanceAttributes(): Collection
    {
        return $this->wechatPerformanceAttributes;
    }

    public function addWechatPerformanceAttribute(PerformanceAttribute $wechatPerformanceAttribute): void
    {
        if (!$this->wechatPerformanceAttributes->contains($wechatPerformanceAttribute)) {
            $this->wechatPerformanceAttributes->add($wechatPerformanceAttribute);
            $wechatPerformanceAttribute->setPerformance($this);
        }
    }

    public function removeWechatPerformanceAttribute(PerformanceAttribute $wechatPerformanceAttribute): void
    {
        if ($this->wechatPerformanceAttributes->removeElement($wechatPerformanceAttribute)) {
            // set the owning side to null (unless already changed)
            if ($wechatPerformanceAttribute->getPerformance() === $this) {
                $wechatPerformanceAttribute->setPerformance(null);
            }
        }
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
