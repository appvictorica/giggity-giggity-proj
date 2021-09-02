<?php

namespace Amasty\VictoriaModule\Model;

use Amasty\VictoriaModule\Model\BlackListFactory;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList as BlackListResource;

class BlackListRepository
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
        BlackListFactory  $blackListFactory,
        BlackListResource $blackListResource
    ) {
        $this->blackListFactory = $blackListFactory;
        $this->blackListResource = $blackListResource;
    }

    public function getById(int $id)
    {
        $blackListModel = $this->blackListFactory->create();
        $this->blackListResource->load($blackListModel, $id);

        return $blackListModel;
    }

    public function saveBlackList($blackListModel)
    {
        $this->blackListResource->save($blackListModel);
    }
}
