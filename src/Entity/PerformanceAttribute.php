<?php

declare(strict_types=1);

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramStatsBundle\Repository\PerformanceAttributeRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: PerformanceAttributeRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_performance_attribute', options: ['comment' => '微信小程序性能属性表'])]
class PerformanceAttribute implements AdminArrayInterface, \Stringable
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

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '属性名称'])]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::STRING, length: 500, nullable: true, options: ['comment' => '属性值'])]
    #[Assert\Length(max: 500)]
    private ?string $value = null;

    #[Ignore]
    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'wechatPerformanceAttributes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Performance $performance = null;

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'performance' => $this->getPerformance()?->getId(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPerformance(): ?Performance
    {
        return $this->performance;
    }

    public function setPerformance(?Performance $performance): void
    {
        $this->performance = $performance;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
