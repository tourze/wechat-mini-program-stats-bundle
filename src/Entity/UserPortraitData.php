<?php

namespace WechatMiniProgramStatsBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDataRepository;

#[ORM\Entity(repositoryClass: UserPortraitDataRepository::class)]
#[ORM\Table(name: 'wechat_user_access_portrait_data', options: ['comment' => '用户画像分布数据'])]
#[ORM\UniqueConstraint(name: 'wechat_user_access_portrait_data_uniq', columns: ['date', 'name', 'account_id', 'type'])]
class UserPortraitData implements AdminArrayInterface
, \Stringable{
    use SnowflakeKeyAware;
    use CreateTimeAware;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    private ?string $date = null;

    private ?string $type = null;

    private ?\DateTimeInterface $beginTime = null;

    private ?\DateTimeInterface $endTime = null;

    private ?string $userType = null;

    private ?string $province = null;

    private ?string $name = null;

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

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getBeginTime(): ?\DateTimeInterface
    {
        return $this->beginTime;
    }

    public function setBeginTime(?\DateTimeInterface $beginTime): self
    {
        $this->beginTime = $beginTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
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

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

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
