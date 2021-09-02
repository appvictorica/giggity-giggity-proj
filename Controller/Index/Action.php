<?php

namespace Amasty\VictoriaModule\Controller\Index;

use Amasty\VictoriaModule\Model\Config\ConfigProvider;
use Amasty\VictoriaModule\Model\BlackListFactory;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList as BlackListResource;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action as MagentoAction;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
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

    /**
     * @var BlackListFactory
     */
    private $blackListFactory;

    /**
     * @var BlackListResource
     */
    private $blackListResource;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        Context                    $context,
        Session                    $session,
        ProductRepositoryInterface $productRepository,
        ManagerInterface           $manager,
        StoreManagerInterface      $storeManager,
        ConfigProvider             $configProvider,
        BlackListFactory           $blackListFactory,
        BlackListResource          $blackListResource,
        CollectionFactory          $collectionFactory
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->manager = $manager;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
        $this->blackListFactory = $blackListFactory;
        $this->blackListResource = $blackListResource;
        $this->collectionFactory = $collectionFactory;
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

            if ($quote->getItems()) {
                foreach ($quote->getItems() as $item) {
                    $collectionBlacklist = $this->collectionFactory->create();
                    $blackList = $collectionBlacklist->addFieldToFilter('sku', $sku)->getFirstItem();

                    if ($sku == $blackList->getSku()) {
                        $maxQty = $blackList->getQty();
                        $sumQty = $qty + $item->getQty();

                        if ($sumQty > $maxQty) {
                            if ($item->getQty() >= $maxQty) {
                                $this->messageManager->addNoticeMessage(__('Product limit exceeded (shopping cart)'));
                            } elseif ($qty >= $maxQty) {
                                $qty = $maxQty;
                                $this->messageManager->addNoticeMessage(__('Product limit exceeded (action form)'));
                            }

                            if($sku === $item->getSku()) {
                                $qty = $maxQty - $item->getQty();
                            }
                            if ($qty < 0) {
                                $qty = 0;
                            }
                            $this->messageManager->addNoticeMessage(__('Added product = %1', $qty));
                        }
                    }
                }
            } else {
                $collectionBlacklist = $this->collectionFactory->create();
                $blackList = $collectionBlacklist->addFieldToFilter('sku', $sku)->getFirstItem();

                if ($blackList) {
                    $maxQty = $blackList->getQty();
                    if ($qty > $maxQty) {
                        $qty = $maxQty;
                        $this->messageManager->addNoticeMessage(__('Product limit exceeded. Added product = %1', $qty));
                    }
                }
            }

            if ($product->getTypeId() == 'simple') {
                if (!$quote->getId()) {
                    $quote->save();
                }
                if($qty > 0 ) {
                    $quote->addProduct($product, $qty);
                    $quote->save();
                }

                $this->_eventManager->dispatch(
                    'give_promo_product',
                    ['product' => $product]
                );
            } else {
                $this->messageManager->addErrorMessage(__('Product with sku %1 is not simple ', $sku));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        $this->_redirect('*/');
    }
}
