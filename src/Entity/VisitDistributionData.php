<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\VisitDistributionDataRepository;

#[AsPermission(title: '获取用户小程序访问分布数据')]
#[Listable]
#[Creatable]
#[ORM\Entity(repositoryClass: VisitDistributionDataRepository::class)]
#[ORM\Table(name: 'wechat_visit_distribution_data', options: ['comment' => '获取用户小程序访问分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_visit_distribution_data_uniq', columns: ['date', 'account_id', 'type', 'scene_id', 'scene_id_pv'])]
class VisitDistributionData implements AdminArrayInterface
{
    use CreateTimeAware;

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ListColumn]
    #[ORM\Column(name: 'date', type: Types::DATE_MUTABLE, nullable: false, options: ['comment' => '数据日期'])]
    private ?\DateTimeInterface $date = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '分布类型'])]
    private ?string $type = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '场景id'])]
    private ?string $sceneId = null;

    #[ListColumn]
    #[ORM\Column(length: 200, options: ['comment' => '该场景id访问PV'])]
    private ?string $sceneIdPv = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    public function getId(): ?string
    {
        return $this->id;
    }

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

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
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
}
