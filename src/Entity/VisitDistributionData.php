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
use WechatMiniProgramStatsBundle\Repository\VisitDistributionDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: VisitDistributionDataRepository::class)]
#[ORM\Table(name: 'wechat_visit_distribution_data', options: ['comment' => '获取用户小程序访问分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_visit_distribution_data_uniq', columns: ['date', 'account_id', 'type', 'scene_id', 'scene_id_pv'])]
class VisitDistributionData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '类型'])]
    #[Assert\Length(max: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '场景 ID'])]
    #[Assert\Length(max: 255)]
    private ?string $sceneId = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '场景 ID PV'])]
    #[Assert\Length(max: 255)]
    private ?string $sceneIdPv = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'type' => $this->getType(),
            'sceneId' => $this->getSceneId(),
            'sceneIdPv' => $this->getSceneIdPv(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function getSceneIdPv(): ?string
    {
        return $this->sceneIdPv;
    }

    public function setSceneIdPv(?string $sceneIdPv): void
    {
        $this->sceneIdPv = $sceneIdPv;
    }

    public function getSceneId(): ?string
    {
        return $this->sceneId;
    }

    public function setSceneId(?string $sceneId): void
    {
        $this->sceneId = $sceneId;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
