<?php

namespace Amasty\VictoriaModule\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class BlackList
 * @method string setSku(string $sku)
 * @method int setQty(int $qty)
 * @method string setEmailBody(string $emailBody)
 *
 */
class BlackList extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            ResourceModel\BlackList::class
        );
    }

}
