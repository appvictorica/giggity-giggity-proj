<?php

namespace Amasty\VictoriaModule\Block\Email;

use Magento\Framework\View\Element\Template;

class BlackList extends Template
{
    public function getSku()
    {
        return $this->getData('sku');
    }

    public function getQty()
    {
        return $this->getData('qty');
    }
}
