<?php

namespace WechatMiniProgramStatsBundle\Tests\Service;

use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramStatsBundle\Entity\UserPortraitProvinceData;
use WechatMiniProgramStatsBundle\Repository\UserPortraitAgeDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitCityDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitDeviceDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitGendersDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitPlatformDataRepository;
use WechatMiniProgramStatsBundle\Repository\UserPortraitProvinceDataRepository;
use WechatMiniProgramStatsBundle\Service\WechatUserPortraitService;

class WechatUserPortraitServiceTest extends TestCase
{
    private WechatUserPortraitService $service;
    private MockObject|UserPortraitProvinceDataRepository $provinceRepository;
    private MockObject|EntityManagerInterface $entityManager;
    private MockObject|UserPortraitCityDataRepository $cityRepository;
    private MockObject|UserPortraitGendersDataRepository $gendersRepository;
    private MockObject|UserPortraitPlatformDataRepository $platformRepository;
    private MockObject|UserPortraitDeviceDataRepository $deviceRepository;
    private MockObject|UserPortraitAgeDataRepository $ageRepository;
    private MockObject|Client $client;
    private MockObject|LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->provinceRepository = $this->createMock(UserPortraitProvinceDataRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->cityRepository = $this->createMock(UserPortraitCityDataRepository::class);
        $this->gendersRepository = $this->createMock(UserPortraitGendersDataRepository::class);
        $this->platformRepository = $this->createMock(UserPortraitPlatformDataRepository::class);
        $this->deviceRepository = $this->createMock(UserPortraitDeviceDataRepository::class);
        $this->ageRepository = $this->createMock(UserPortraitAgeDataRepository::class);
        $this->client = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        $this->service = new WechatUserPortraitService(
            $this->provinceRepository,
            $this->entityManager,
            $this->cityRepository,
            $this->gendersRepository,
            $this->platformRepository,
            $this->deviceRepository,
            $this->ageRepository,
            $this->client,
            $this->logger
        );
    }

    public function testGetDate_withSuccessfulResponse_persistsAllData(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $start = CarbonImmutable::parse('2023-01-01');
        $end = CarbonImmutable::parse('2023-01-07');
        
        // 模拟API响应
        $apiResponse = [
            'ref_date' => '20230101-20230107',
            'visit_uv_new' => [
                'province' => [
                    ['name' => 'Beijing', 'value' => 100, 'id' => '110000'],
                ],
                'city' => [
                    ['name' => 'Beijing', 'value' => 100, 'id' => '110100'],
                ],
                'genders' => [
                    ['name' => 'male', 'value' => 60, 'id' => '1'],
                    ['name' => 'female', 'value' => 40, 'id' => '2'],
                ],
                'platforms' => [
                    ['name' => 'ios', 'value' => 40, 'id' => 'ios'],
                    ['name' => 'android', 'value' => 60, 'id' => 'android'],
                ],
                'devices' => [
                    ['name' => 'iPhone', 'value' => 40, 'id' => 'iPhone'],
                    ['name' => 'Huawei', 'value' => 60, 'id' => 'Huawei'],
                ],
                'ages' => [
                    ['name' => '25-35', 'value' => 60, 'id' => '25-35'],
                    ['name' => '35-45', 'value' => 40, 'id' => '35-45'],
                ],
            ],
        ];
        
        // 配置模拟对象的行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟findOneBy返回null，表示所有数据都是新数据
        $this->provinceRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        
        $this->cityRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        
        $this->gendersRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);
        
        $this->platformRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);
        
        $this->deviceRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);
        
        $this->ageRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);
        
        // 验证EntityManager方法调用
        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');
        
        $this->entityManager->expects($this->atLeastOnce())
            ->method('flush');
        
        $this->entityManager->expects($this->atLeastOnce())
            ->method('detach');
        
        // 执行测试方法
        $this->service->getDate($account, $start, $end);
    }

    public function testGetDate_withExistingData_updatesInsteadOfCreating(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $start = CarbonImmutable::parse('2023-01-01');
        $end = CarbonImmutable::parse('2023-01-07');
        
        // 模拟现有实体
        $existingProvinceData = new UserPortraitProvinceData();
        $existingProvinceData->setAccount($account);
        $existingProvinceData->setType('visit_uv_new');
        $existingProvinceData->setDate('20230101-20230107');
        $existingProvinceData->setName('Beijing');
        $existingProvinceData->setValue('50');
        
        // 模拟API响应
        $apiResponse = [
            'ref_date' => '20230101-20230107',
            'visit_uv_new' => [
                'province' => [
                    ['name' => 'Beijing', 'value' => 100, 'id' => '110000'],
                ],
                'city' => [
                    ['name' => 'Beijing', 'value' => 100, 'id' => '110100'],
                ],
                'genders' => [],
                'platforms' => [],
                'devices' => [],
                'ages' => [],
            ],
        ];
        
        // 配置模拟对象的行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 模拟findOneBy返回已存在的数据
        $this->provinceRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingProvinceData);
        
        $this->cityRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        
        // 验证EntityManager方法调用
        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');
        
        $this->entityManager->expects($this->atLeastOnce())
            ->method('flush');
        
        $this->entityManager->expects($this->atLeastOnce())
            ->method('detach');
        
        // 执行测试方法
        $this->service->getDate($account, $start, $end);
        
        // 验证现有对象的值已更新
        $this->assertEquals('100', $existingProvinceData->getValue());
        $this->assertEquals('110000', $existingProvinceData->getValueId());
    }

    public function testGetDate_withException_logsErrorAndReturnsNull(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $start = CarbonImmutable::parse('2023-01-01');
        $end = CarbonImmutable::parse('2023-01-07');
        
        $exception = new \Exception('API Error');
        
        // 配置模拟对象的行为
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException($exception);
        
        // 验证Logger被调用
        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('获取用户用户画像分布时发生异常'),
                $this->callback(function ($context) use ($exception, $account) {
                    return isset($context['account']) && 
                           isset($context['exception']) && 
                           $context['account'] === $account &&
                           $context['exception'] === $exception;
                })
            );
        
        // 验证EntityManager方法不会被调用
        $this->entityManager->expects($this->never())
            ->method('persist');
        
        $this->entityManager->expects($this->never())
            ->method('flush');
        
        // 执行测试方法并验证结果
        $result = $this->service->getDate($account, $start, $end);
        $this->assertNull($result);
    }

    public function testGetDate_withEmptyResponse_doesNotPersistData(): void
    {
        // 创建测试数据
        $account = $this->createMock(Account::class);
        $start = CarbonImmutable::parse('2023-01-01');
        $end = CarbonImmutable::parse('2023-01-07');
        
        // 模拟API响应 - 不包含visit_uv_new
        $apiResponse = [
            'ref_date' => '20230101-20230107',
        ];
        
        // 配置模拟对象的行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($apiResponse);
        
        // 验证EntityManager方法不会被调用
        $this->entityManager->expects($this->never())
            ->method('persist');
        
        $this->entityManager->expects($this->never())
            ->method('flush');
        
        // 执行测试方法
        $this->service->getDate($account, $start, $end);
    }
} 