<?php

namespace Amasty\VictoriaModule\Setup;

use Amasty\VictoriaModule\Model\BlackListFactory;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList as BlackListResource;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var BlackListFactory
     */
    private $blackListFactory;
    /**
     * @var BlackListResource
     */
    private $blackListResource;

    public function __construct(
        BlackListFactory $blackListFactory,
        BlackListResource     $blackListResource
    )
    {
        $this->blackListFactory = $blackListFactory;
        $this->blackListResource = $blackListResource;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Amasty\VictoriaModule\Model\BlackList $blackList */

        $data = [
            [
                'sku' => '24-MB02',
                'qty' => '2'
            ],
            [
                'sku' => '24-MB03',
                'qty' => '3'
            ],
            [
                'sku' => '24-MB04',
                'qty' => '4'
            ]
        ];

            foreach ($data as $item) {
                $blackList = $this->blackListFactory->create();
                $blackList->setSku($item['sku']);
                $blackList->setQty($item['qty']);
                $this->blackListResource->save($blackList);
            }
    }
}
