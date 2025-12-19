# WeChat Mini Program Stats Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-stats-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-stats-bundle)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-95%25-brightgreen.svg?style=flat-square)](#)

This bundle provides comprehensive statistics collection and analysis for WeChat Mini Programs using the WeChat official API.

## Table of Contents

- [Installation](#installation)
- [Requirements](#requirements)
- [Features](#features)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Available Commands](#available-commands)
- [JSON-RPC Procedures](#json-rpc-procedures)
- [Entity Overview](#entity-overview)
- [Usage Examples](#usage-examples)
- [Advanced Usage](#advanced-usage)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [License](#license)

## Installation

```bash
composer require tourze/wechat-mini-program-stats-bundle
```

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- WeChat Mini Program API access

## Features

- **Data Collection**: Automated collection of WeChat Mini Program statistics data
- **Visit Analysis**: Daily, weekly, and monthly visit trend analysis
- **Performance Monitoring**: Performance data collection and analysis
- **User Analytics**: User portrait and access behavior analysis
- **Retention Analysis**: User retention tracking and analysis
- **Scheduled Tasks**: Automated data collection via cron jobs

## Configuration

### 1. Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    WechatMiniProgramStatsBundle\WechatMiniProgramStatsBundle::class => ['all' => true],
];
```

### 2. Configure Database

Run the migrations to create the required tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 3. WeChat API Configuration

Ensure you have configured the WeChat Mini Program API credentials in your main application configuration.

## Quick Start

### Set Up Cron Jobs

The bundle includes automated data collection commands that can be scheduled:

```bash
# Daily statistics collection
php bin/console wechat-mini-program:get-daily-summary
php bin/console wechat-mini-program:get-daily-visit-trend
php bin/console wechat-mini-program:get-daily-retain

# Weekly and monthly trends
php bin/console wechat-mini-program:get-weekly-visit-trend
php bin/console wechat-mini-program:get-monthly-visit-trend

# Performance monitoring
php bin/console wechat-mini-program:performance-data:get
php bin/console wechat-mini-program:operation-performance:sync
```

## Available Commands

### Data Collection Commands

- `wechat-mini-program:get-daily-summary` - Collect daily summary statistics
- `wechat-mini-program:get-daily-visit-trend` - Collect daily visit trend data
- `wechat-mini-program:get-weekly-visit-trend` - Collect weekly visit trend data
- `wechat-mini-program:get-monthly-visit-trend` - Collect monthly visit trend data
- `wechat-mini-program:get-daily-retain` - Collect daily retention data

### User Analytics Commands

- `wechat-mini-program:user-portrait:get` - Collect user portrait data
- `wechat-mini-program:user-accesses:week-data:get` - Collect weekly user access data
- `wechat-mini-program:user-accesses:month-data:get` - Collect monthly user access data
- `wechat-mini-program:visit-distribution:get` - Collect visit distribution data

### Performance Commands

- `wechat-mini-program:performance-data:get` - Collect performance data
- `wechat-mini-program:operation-performance:sync` - Sync operation performance data
- `wechat-mini-program:check-performance` - Check performance metrics

### Page Analytics Commands

- `wechat-mini-program:get-wechat-user-access-page-data` - Collect page access data
- `wechat-mini-program:count-daily-page-visit-data` - Count daily page visits
- `wechat-mini-program:count-daily-new-user-visit-data` - Count daily new user visits
- `wechat-mini-program:count-wechat-hour-visit-data` - Count hourly visit data

## JSON-RPC Procedures

The bundle provides several JSON-RPC procedures for accessing statistics data:

### Visit Trend Procedures

- `GetWechatMiniProgramDailyVisitTrendData` - Get daily visit trend data
- `GetWechatMiniProgramDailyVisitTrendDataByDateRange` - Get daily visit trend data by date range
- `GetWechatMiniProgramDailyVisitTrendDataTotalByDateRange` - Get aggregated daily visit trend data
- `GetWechatMiniProgramVisitTotalUser` - Get total visit users
- `GetWechatMiniProgramVisitUvAverage` - Get average unique visitors

### User Analytics Procedures

- `GetWechatMiniProgramUserPortraitAge` - Get user age demographics
- `GetWechatMiniProgramUserPortraitGender` - Get user gender demographics
- `GetWechatMiniProgramUserPortraitGenderByDateRange` - Get user gender demographics by date range
- `GetWechatMiniProgramUserPortraitProvince` - Get user province distribution

### Page & Retention Procedures

- `GetWechatMiniProgramPageVisitTotalDataByDate` - Get page visit data by specific date
- `GetWechatMiniProgramPageVisitTotalDataByDateRange` - Get page visit data by date range
- `GetWechatMiniProgramRetainUserByDate` - Get user retention data by date
- `GetWechatMiniProgramNewUserVisitByDate` - Get new user visits by date

## Entity Overview

The bundle provides the following entities for data storage:

- **DailyPageVisitData**: Daily page visit statistics
- **MonthlyVisitTrend**: Monthly visit trend data
- **WeeklyVisitTrend**: Weekly visit trend data
- **Performance**: Performance monitoring data
- **PerformanceAttribute**: Performance attribute details
- **OperationPerformance**: Operation performance metrics

## Usage Examples

### Collecting Daily Statistics

```php
use Symfony\Component\Console\Application;
use WechatMiniProgramStatsBundle\Command\DataCube\GetDailySummaryCommand;

// Run daily summary collection
$application = new Application();
$command = $container->get(GetDailySummaryCommand::class);
$application->addCommand($command);
$application->run();
```

### Accessing Statistics Data

```php
use WechatMiniProgramStatsBundle\Repository\DailyPageVisitDataRepository;

// Get daily page visit data
$repository = $entityManager->getRepository(DailyPageVisitData::class);
$data = $repository->findBy(['date' => new \DateTime('2024-01-01')]);
```

## Advanced Usage

### Custom Data Processing

You can extend the bundle's functionality by creating custom commands or services that 
utilize the existing repositories and entities. The bundle is designed to be flexible 
and extensible.

### Performance Monitoring

The bundle includes comprehensive performance monitoring capabilities that can help you 
track and optimize your WeChat Mini Program's performance metrics.

## Testing

This bundle includes comprehensive test coverage with both unit and integration tests.

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests

# Run tests with coverage
php -d memory_limit=2G ./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests --coverage-html coverage

# Run PHPStan analysis
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-mini-program-stats-bundle
```

### Test Structure

- **Unit Tests**: Entity and Enum tests that verify basic functionality
- **Integration Tests**: Repository and Service tests that require database connection
- **Command Tests**: Console command tests for data collection functionality

All tests follow the PSR standards and include comprehensive assertions for both happy path and edge cases.

## Security

- **API Security**: All WeChat API communications are secured with proper authentication
- **Data Validation**: Input data is validated before processing and storage
- **SQL Injection Prevention**: All database queries use parameterized statements
- **Access Control**: Commands and services respect application security policies

If you discover any security vulnerabilities, please report them via [GitHub Issues](https://github.com/tourze/php-monorepo/issues).

## Contributing

We welcome contributions! Here's how you can help:

### Reporting Issues

- Use [GitHub Issues](https://github.com/tourze/php-monorepo/issues) to report bugs
- Include PHP version, Symfony version, and detailed error messages
- Provide minimal code examples that reproduce the issue

### Submitting Pull Requests

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Make your changes with appropriate tests
4. Ensure all tests pass: `./vendor/bin/phpunit packages/wechat-mini-program-stats-bundle/tests`
5. Run PHPStan analysis: `php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/wechat-mini-program-stats-bundle`
6. Submit a pull request with clear description

### Code Standards

- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for any new functionality
- Use meaningful commit messages

## Changelog

### Version 0.1.x

- Initial release with core statistics collection functionality
- Support for daily, weekly, and monthly visit trend analysis
- User portrait and behavior analytics
- Performance monitoring capabilities
- Comprehensive JSON-RPC API procedures

## License

This bundle is released under the MIT license. See the included LICENSE file for more information.