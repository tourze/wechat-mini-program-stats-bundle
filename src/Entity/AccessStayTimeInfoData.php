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
use WechatMiniProgramStatsBundle\Repository\AccessStayTimeInfoDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: AccessStayTimeInfoDataRepository::class)]
#[ORM\Table(name: 'wechat_access_staytime_info_data', options: ['comment' => '获取用户小程序访问分布数据(访问来源分布)'])]
#[ORM\UniqueConstraint(name: 'wechat_access_staytime_info_data_uniq', columns: ['date', 'account_id', 'data_key'])]
class AccessStayTimeInfoData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '数据字段'])]
    #[Assert\Length(max: 255)]
    private ?string $dataKey = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '数据字段'])]
    #[Assert\Length(max: 255)]
    private ?string $dataValue = null;

    public function getDataValue(): ?string
    {
        return $this->dataValue;
    }

    public function setDataValue(?string $dataValue): void
    {
        $this->dataValue = $dataValue;
    }

    public function getDataKey(): ?string
    {
        return $this->dataKey;
    }

    public function setDataKey(?string $dataKey): void
    {
        $this->dataKey = $dataKey;
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

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'dataKey' => $this->getDataKey(),
            'dataValue' => $this->getDataValue(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
