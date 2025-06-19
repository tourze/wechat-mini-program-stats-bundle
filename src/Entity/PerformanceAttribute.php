<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramStatsBundle\Repository\PerformanceAttributeRepository;

#[ORM\Entity(repositoryClass: PerformanceAttributeRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_performance_attribute', options: ['comment' => '微信小程序性能属性表'])]
class PerformanceAttribute implements AdminArrayInterface
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

    #[Groups(['admin_curd'])]
    private ?string $name = null;

    #[Groups(['admin_curd'])]
    private ?string $value = null;

    #[Ignore]
    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'wechatPerformanceAttributes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Performance $performance;
public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'performance' => $this->getPerformance()->getId(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getPerformance(): Performance
    {
        return $this->performance;
    }

    public function setPerformance(?Performance $performance): static
    {
        $this->performance = $performance;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
