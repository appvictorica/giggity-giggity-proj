<?php

namespace Amasty\VictoriaModule\Block;

use Amasty\VictoriaModule\Model\Config\ConfigProvider;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    const FORM_ACTION = 'victoriamod/index/action';
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ConfigProvider
     */
    private $configProvider;
    /**
     * @var int
     */
    private $storeId;

    public function __construct(
        Template\Context     $context,
        StoreManagerInterface $storeManager,
        ConfigProvider $configProvider,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
        $this->setStoreId();
    }

    private function setStoreId()
    {
        $this->storeId = $this->storeManager->getStore()->getId();
    }

    public function greetingTextFromSettings()
    {
        return $this->configProvider->getGreetingText($this->storeId);
    }

    public function setVisibleElement()
    {
        if (!$this->configProvider->getIsShowQtyField($this->storeId)) {
            return $this->getData('hide_element');
        } else {
            return $this->getData("input_class_qty");
        }
    }

    public function getQtyDefault()
    {
        if ($this->configProvider->getIsShowQtyField($this->storeId) &&
            $this->configProvider->getQtyDefault($this->storeId)) {
            return $this->configProvider->getQtyDefault($this->storeId);
        }
    }

    public function helloMessage()
    {
        return 'Hello World :)';
    }

    public function getFormAction()
    {
        return $this->getUrl(self::FORM_ACTION);
    }

}
