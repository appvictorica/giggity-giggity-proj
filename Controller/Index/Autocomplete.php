<?php

namespace Amasty\VictoriaModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action as MagentoAction;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Autocomplete extends MagentoAction {

    /**
     * @var Context
     */
    private $context;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $collectionFactory
    )
    {
        $this->context = $context;
        $this->jsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $sku = $this->_request->getParam('sku');
        if ($this->getRequest()->isAjax())
        {
            $collection = $this->collectionFactory->create();
            $collection
                ->addAttributeToSelect(['name', 'sku'])
                ->addAttributeToFilter('sku', ['like' => '%'.$sku.'%'])
                ->setPageSize(10);
            $data = [];
            foreach ($collection->getItems() as $item)
            {
                $data[]=[
                    'productSku' => $item->getSku(),
                    'productName' => $item->getName()
                ];
            }
            $productInfo=$data;
            return $result->setData($productInfo);
        }
    }

}
