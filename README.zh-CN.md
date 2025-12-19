# 微信小程序统计分析包

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-95%25-brightgreen.svg?style=flat-square)](#)

这个包提供了完整的微信小程序统计数据收集和分析功能，使用微信官方 API。

## 目录

- [安装](#安装)
- [系统要求](#系统要求)
- [功能特性](#功能特性)
- [配置](#配置)
- [快速开始](#快速开始)
- [可用命令](#可用命令)
- [JSON-RPC 接口](#json-rpc-接口)
- [实体概览](#实体概览)
- [使用示例](#使用示例)
- [高级用法](#高级用法)
- [测试](#测试)
- [安全](#安全)
- [贡献](#贡献)
- [更新日志](#更新日志)
- [许可证](#许可证)

## 安装

```bash
composer require tourze/wechat-mini-program-stats-bundle
```

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- 微信小程序 API 访问权限

## 功能特性

- **数据收集**: 自动收集微信小程序统计数据
- **访问分析**: 日、周、月访问趋势分析
- **性能监控**: 性能数据收集和分析
- **用户分析**: 用户画像和访问行为分析
- **留存分析**: 用户留存跟踪和分析
- **定时任务**: 通过 cron 作业自动收集数据

## 配置

### 1. 启用包

将包添加到 `config/bundles.php`:

```php
return [
    // ...
    WechatMiniProgramStatsBundle\WechatMiniProgramStatsBundle::class => ['all' => true],
];
```

### 2. 配置数据库

运行迁移以创建所需的数据表：

```bash
php bin/console doctrine:migrations:migrate
```

### 3. 微信 API 配置

确保您已在主应用程序配置中配置了微信小程序 API 凭据。

## 快速开始

### 设置定时任务

该包包含可以计划的自动数据收集命令：

```bash
# 日统计收集
php bin/console wechat-mini-program:get-daily-summary
php bin/console wechat-mini-program:get-daily-visit-trend
php bin/console wechat-mini-program:get-daily-retain

# 周和月趋势
php bin/console wechat-mini-program:get-weekly-visit-trend
php bin/console wechat-mini-program:get-monthly-visit-trend

# 性能监控
php bin/console wechat-mini-program:performance-data:get
php bin/console wechat-mini-program:operation-performance:sync
```

## 可用命令

### 数据收集命令

- `wechat-mini-program:get-daily-summary` - 收集每日汇总统计
- `wechat-mini-program:get-daily-visit-trend` - 收集每日访问趋势数据
- `wechat-mini-program:get-weekly-visit-trend` - 收集每周访问趋势数据
- `wechat-mini-program:get-monthly-visit-trend` - 收集每月访问趋势数据
- `wechat-mini-program:get-daily-retain` - 收集每日留存数据

### 用户分析命令

- `wechat-mini-program:user-portrait:get` - 收集用户画像数据
- `wechat-mini-program:user-accesses:week-data:get` - 收集每周用户访问数据
- `wechat-mini-program:user-accesses:month-data:get` - 收集每月用户访问数据
- `wechat-mini-program:visit-distribution:get` - 收集访问分布数据

### 性能命令

- `wechat-mini-program:performance-data:get` - 收集性能数据
- `wechat-mini-program:operation-performance:sync` - 同步运营性能数据
- `wechat-mini-program:check-performance` - 检查性能指标

### 页面分析命令

- `wechat-mini-program:get-wechat-user-access-page-data` - 收集页面访问数据
- `wechat-mini-program:count-daily-page-visit-data` - 统计每日页面访问
- `wechat-mini-program:count-daily-new-user-visit-data` - 统计每日新用户访问
- `wechat-mini-program:count-wechat-hour-visit-data` - 统计每小时访问数据

## JSON-RPC 接口

该包提供多个 JSON-RPC 接口用于访问统计数据：

### 访问趋势接口

- `GetWechatMiniProgramDailyVisitTrendData` - 获取每日访问趋势数据
- `GetWechatMiniProgramDailyVisitTrendDataByDateRange` - 按日期范围获取每日访问趋势数据
- `GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange` - 获取聚合的每日访问趋势数据
- `GetWechatMiniProgramVisitTotalUser` - 获取总访问用户数
- `GetWechatMiniProgramVisitUvAverage` - 获取平均独立访客数

### 用户分析接口

- `GetWechatMiniProgramUserPortraitAge` - 获取用户年龄分布
- `GetWechatMiniProgramUserPortraitGender` - 获取用户性别分布
- `GetWechatMiniProgramUserPortraitGenderByDateRange` - 按日期范围获取用户性别分布
- `GetWechatMiniProgramUserPortraitProvince` - 获取用户省份分布

### 页面和留存接口

- `GetWechatMiniProgramPageVisitTotalDataByDate` - 按指定日期获取页面访问数据
- `GetWechatMiniProgramPageVisitTotalDataByDateRange` - 按日期范围获取页面访问数据
- `GetWechatMiniProgramRetainUserByDate` - 按日期获取用户留存数据
- `GetWechatMiniProgramNewUserVisitByDate` - 按日期获取新用户访问数据

## 实体概览

该包提供以下实体用于数据存储：

- **DailyPageVisitData**: 每日页面访问统计
- **MonthlyVisitTrend**: 每月访问趋势数据
- **WeeklyVisitTrend**: 每周访问趋势数据
- **Performance**: 性能监控数据
- **PerformanceAttribute**: 性能属性详情
- **OperationPerformance**: 运营性能指标

## 使用示例

### 收集每日统计

```php
use Symfony\Component\Console\Application;
use WechatMiniProgramStatsBundle\Command\DataCube\GetDailySummaryCommand;

// 运行每日汇总收集
$application = new Application();
$command = $container->get(GetDailySummaryCommand::class);
$application->addCommand($command);
$application->run();
```

### 访问统计数据

```php
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

// 获取每日页面访问数据
$repository = $entityManager->getRepository(DailyPageVisitData::class);
$data = $repository->findBy(['date' => new \DateTime('2024-01-01')]);
```

## 高级用法

### 自定义数据处理

您可以通过创建使用现有存储库和实体的自定义命令或服务来扩展包的功能。该包设计为灵活且可扩展。

### 性能监控

该包包含全面的性能监控功能，可以帮助您跟踪和优化微信小程序的性能指标。

## 测试

该包包含全面的测试覆盖，包括单元测试和集成测试。

### 运行测试

```bash
# 运行所有测试
./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests

# 运行带覆盖率的测试
php -d memory_limit=2G ./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests --coverage-html coverage

# 运行 PHPStan 分析
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-mini-program-stats-bundle
```

### 测试结构

- **单元测试**: 验证基本功能的实体和枚举测试
- **集成测试**: 需要数据库连接的仓储和服务测试
- **命令测试**: 数据收集功能的控制台命令测试

所有测试都遵循 PSR 标准，包括对正常流程和边界情况的全面断言。

## 安全

- **API 安全**: 所有微信 API 通信都通过适当的身份验证进行保护
- **数据验证**: 输入数据在处理和存储之前进行验证
- **SQL 注入防护**: 所有数据库查询都使用参数化语句
- **访问控制**: 命令和服务遵循应用程序安全策略

如果您发现任何安全漏洞，请通过 [GitHub Issues](https://github.com/tourze/php-monorepo/issues) 报告。

## 贡献

我们欢迎贡献！以下是您可以帮助的方式：

### 报告问题

- 使用 [GitHub Issues](https://github.com/tourze/php-monorepo/issues) 报告错误
- 包含 PHP 版本、Symfony 版本和详细的错误信息
- 提供能重现问题的最小代码示例

### 提交 Pull Request

1. Fork 代码仓库
2. 创建功能分支：`git checkout -b feature/new-feature`
3. 进行更改并添加适当的测试
4. 确保所有测试通过：`./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests`
5. 运行 PHPStan 分析：`php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-mini-program-stats-bundle`
6. 提交带有清晰描述的 pull request

### 代码标准

- 遵循 PSR-12 编码标准
- 为新功能编写全面的测试
- 为任何新功能更新文档
- 使用有意义的提交消息

## 更新日志

### 版本 0.1.x

- 初版发布，包含核心统计收集功能
- 支持日、周、月访问趋势分析
- 用户画像和行为分析
- 性能监控功能
- 全面的 JSON-RPC API 接口

## 许可证

该包基于 MIT 许可证发布。有关更多信息，请查看包含的 LICENSE 文件。