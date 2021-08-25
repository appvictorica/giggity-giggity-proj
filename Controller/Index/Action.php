<?php

namespace Amasty\VictoriaModule\Controller\Index;

use Amasty\VictoriaModule\Model\Config\ConfigProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action as MagentoAction;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Action extends MagentoAction
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        Context                    $context,
        Session                    $session,
        ProductRepositoryInterface $productRepository,
        ManagerInterface           $manager,
        StoreManagerInterface $storeManager,
        ConfigProvider        $configProvider
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->manager = $manager;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
    }

    public function execute()
    {
        $sku = $this->_request->getParam('sku');
        if ($this->configProvider->getIsShowQtyField($this->storeManager->getStore()->getId())) {
            $qty = $this->_request->getParam('qty');
        } else {
            $qty = 1;
        }

        try {
            $product = $this->productRepository->get($sku);
            $quote = $this->session->getQuote();

            if ($product->getTypeId() == 'simple') {

                if (!$quote->getId()) {
                    $quote->save();
                }
                $quote->addProduct($product, $qty);
                $quote->save();

                $this->_eventManager->dispatch(
                    'give_promo_product',
                    ['product' => $product]
                );
            } else {
                $this->messageManager->addErrorMessage('Product with sku ' . $sku . ' is not simple ');
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        $this->_redirect('*/');
    }
}
