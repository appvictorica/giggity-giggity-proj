<?php

namespace Amasty\VictoriaModule\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var String
     */
    protected $pathPrefix;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getValue($path, $storeId, $scope = 'store')
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
    }

    public function isSetFlag($path, $storeId, $scope = 'store')
    {
        return $this->scopeConfig->isSetFlag($this->pathPrefix . $path, $scope, $storeId);
    }
}
