<?php

namespace Amasty\VictoriaModule\Controller\Index;

use Amasty\VictoriaModule\Model\Config\ConfigProvider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;


class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var ConfigProvider
     */
    private $configProvider;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Context               $context,
        PageFactory           $pageFactory,
        StoreManagerInterface $storeManager,
        ConfigProvider        $configProvider
    ) {
        $this->pageFactory = $pageFactory;
        $this->context = $context;
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
        return parent::__construct($context);
    }

    public function execute()
    {
        if ($this->configProvider->getIsEnabled($this->storeManager->getStore()->getId())) {
            return $this->pageFactory->create();
        } else {
            die("Victoria Module Disabled");
        }
    }
}
