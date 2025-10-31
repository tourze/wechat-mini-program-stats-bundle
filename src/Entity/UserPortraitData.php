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
use WechatMiniProgramStatsBundle\Repository\UserPortraitDataRepository;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: UserPortraitDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_portrait_data', options: ['comment' => '用户画像分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_portrait_data_uniq', columns: ['date', 'name', 'account_id', 'type'])]
class UserPortraitData implements AdminArrayInterface, \Stringable
{
    use SnowflakeKeyAware;
    use CreateTimeAware;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '日期'])]
    #[Assert\Length(max: 255)]
    private ?string $date = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '类型'])]
    #[Assert\Length(max: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '开始时间'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $beginTime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '结束时间'])]
    #[Assert\Type(type: \DateTimeImmutable::class)]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '用户类型'])]
    #[Assert\Length(max: 255)]
    private ?string $userType = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '省份'])]
    #[Assert\Length(max: 255)]
    private ?string $province = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '名称'])]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '值'])]
    #[Assert\Length(max: 255)]
    private ?string $value = null;

    //    private ?string $city = null;
    //
    //    //    #[ORM\Column(length: 100, options: ['comment' => '城市'])]
    //    private ?string $genders = null;
    //
    //    //    #[ORM\Column(length: 100, options: ['comment' => '性别'])]
    //    private ?string $platforms = null;
    //
    //    //    #[ORM\Column(length: 100, options: ['comment' => '终端类型'])]
    //    private ?string $devices = null;
    //
    //    //    #[ORM\Column(length: 100, options: ['comment' => '年龄'])]
    //    private ?string $ages = null;
    //    public function setAges(?string $ages): void
    //    {
    //        $this->ages = $ages;
    //    }
    //    public function getAges(): ?string
    //    {
    //        return $this->ages;
    //    }
    //
    //    public function setDevices(?string $devices): void
    //    {
    //        $this->devices = $devices;
    //    }
    //    public function getDevices(): ?string
    //    {
    //        return $this->devices;
    //    }
    //
    //    public function setPlatforms(?string $platforms): void
    //    {
    //        $this->platforms = $platforms;
    //    }
    //    public function getPlatforms(): ?string
    //    {
    //        return $this->platforms;
    //    }
    //    public function setGenders(?string $genders): void
    //    {
    //        $this->genders = $genders;
    //    }
    //    public function getGenders(): ?string
    //    {
    //        return $this->genders;
    //    }
    //
    //    public function setCity(?string $city): void
    //    {
    //        $this->city = $city;
    //    }
    //    public function getCity(): ?string
    //    {
    //        return $this->city;
    //    }
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setProvince(?string $province): void
    {
        $this->province = $province;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function getBeginTime(): ?\DateTimeImmutable
    {
        return $this->beginTime;
    }

    public function setBeginTime(?\DateTimeImmutable $beginTime): void
    {
        $this->beginTime = $beginTime;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeImmutable $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function setUserType(?string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function setCreateTime(?\DateTimeImmutable $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'type' => $this->getType(),
            'beginTime' => $this->getBeginTime(),
            'endTime' => $this->getEndTime(),
            'userType' => $this->getUserType(),
            'province' => $this->getProvince(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'account' => $this->getAccount(),
            'createTime' => $this->getCreateTime(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
