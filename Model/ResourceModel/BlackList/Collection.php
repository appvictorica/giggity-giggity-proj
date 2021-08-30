<?php

namespace Amasty\VictoriaModule\Model\ResourceModel\BlackList;


use Amasty\VictoriaModule\Model\BlackList;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList as BlackListResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            BlackList::class,
            BlackListResource::class
        );
    }
}
