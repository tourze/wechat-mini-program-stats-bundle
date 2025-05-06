<?php

namespace WechatMiniProgramStatsBundle\Request\DataCube;

use WechatMiniProgramBundle\Request\WithAccountRequest;
use WechatMiniProgramStatsBundle\Enum\CostTimeType;

/**
 * 运维中心-查询性能数据
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/operation/getPerformance.html
 */
class GetOperationPerformanceRequest extends WithAccountRequest
{
    public string $netWorkType;

    public string $scene;

    public string $isDownloadCode;

    public string $device;

    public int $defaultEndTime;

    public int $defaultStartTime;

    public CostTimeType $costTimeType;

    public function __construct()
    {
        $this->defaultEndTime = strtotime('yesterday 23:59:59');
        $this->defaultStartTime = strtotime('yesterday midnight');
        $this->costTimeType = CostTimeType::Launch;
    }

    public function getRequestPath(): string
    {
        return '/wxaapi/log/get_performance';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'cost_time_type' => $this->getCostTimeType()->value,
            'default_start_time' => $this->getDefaultStartTime(),
            'default_end_time' => $this->getDefaultEndTime(),
            'device' => $this->getDevice(),
        ];

        // 是否下载代码包，当 type 为 1 的时候才生效，可选值 "@_all:"（全部），1（是）， 2（否）
        if (CostTimeType::Launch === $this->getCostTimeType()) {
            $json['is_download_code'] = $this->getIsDownloadCode();
        }

        // 网络环境, 当 type 为 2 的时候才生效，可选值 "@_all:"，wifi, 4g, 3g, 2g
        if (CostTimeType::Download === $this->getCostTimeType()) {
            $json['networktype'] = $this->getNetWorkType();
        }

        // 访问来源，当 type 为 1 或者 2 的时候才生效，通过 getSceneList 接口获取
        if (CostTimeType::Launch === $this->getCostTimeType() || CostTimeType::Download === $this->getCostTimeType()) {
            $json['scene'] = $this->getScene();
        }

        return [
            'json' => $json,
        ];
    }

    public function getNetWorkType(): string
    {
        return $this->netWorkType;
    }

    public function setNetWorkType(string $netWorkType): void
    {
        $this->netWorkType = $netWorkType;
    }

    public function getScene(): string
    {
        return $this->scene;
    }

    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    public function getIsDownloadCode(): string
    {
        return $this->isDownloadCode;
    }

    public function setIsDownloadCode(string $isDownloadCode): void
    {
        $this->isDownloadCode = $isDownloadCode;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    public function getDefaultEndTime(): int
    {
        return $this->defaultEndTime;
    }

    public function setDefaultEndTime(int $defaultEndTime): void
    {
        $this->defaultEndTime = $defaultEndTime;
    }

    public function getDefaultStartTime(): int
    {
        return $this->defaultStartTime;
    }

    public function setDefaultStartTime(int $defaultStartTime): void
    {
        $this->defaultStartTime = $defaultStartTime;
    }

    public function getCostTimeType(): CostTimeType
    {
        return $this->costTimeType;
    }

    public function setCostTimeType(CostTimeType $costTimeType): void
    {
        $this->costTimeType = $costTimeType;
    }
}
