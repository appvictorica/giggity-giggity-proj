<?php

namespace Amasty\VictoriaModule\Cron;

use Amasty\VictoriaModule\Model\BlackListFactory;
use Amasty\VictoriaModule\Model\BlackListRepository;
use Amasty\VictoriaModule\Model\Config\ConfigProvider;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList as BlackListResource;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList\Collection;
use Amasty\VictoriaModule\Model\ResourceModel\BlackList\CollectionFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

class SendBlacklistEmail
{
    /**
     * @var CollectionFactory
     */
    private $blackListСollection;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var FactoryInterface
     */
    private $transportBuilder;
    /**
     * @var FactoryInterface
     */
    private $templateFactory;
    /**
     * @var ConfigProvider
     */
    private $configProvider;
    /**
     * @var BlackListRepository
     */
    private $blackListRepository;
    /**
     * @var BlackListFactory
     */
    private $blackListFactory;
    /**
     * @var BlackListResource
     */
    private $blackListResource;

    public function __construct(
        CollectionFactory     $blackListСollection,
        StoreManagerInterface $storeManager,
        TransportBuilder      $transportBuilder,
        FactoryInterface      $templateFactory,
        ConfigProvider        $configProvider,
        BlackListRepository   $blackListRepository,
        BlackListFactory      $blackListFactory,
        BlackListResource     $blackListResource
    ) {
        $this->blackListСollection = $blackListСollection;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->templateFactory = $templateFactory;
        $this->configProvider = $configProvider;
        $this->blackListRepository = $blackListRepository;
        $this->blackListFactory = $blackListFactory;
        $this->blackListResource = $blackListResource;
    }

    public function execute()
    {

        /** @var Collection $blackList */
        $collection = $this->blackListСollection->create();
        $blackListItems = $collection->getItemById(1);

        $templateId = $this->configProvider->getTemplateName($this->storeManager->getStore()->getId());
        $senderName = 'Admin';
        $senderEmail = "admin@mage.com";
        $toEmail = $this->configProvider->getEmailTo($this->storeManager->getStore()->getId());

        $templateVar = [
            'sku' => $blackListItems->getSku(),
            'qty' => $blackListItems->getQty()
        ];
        $storeId = $this->storeManager->getStore()->getId();
        $from = [
            'email' => $senderEmail,
            'name' => $senderName
        ];

        /** @var \Magento\Email\Model\Transport $transport */
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId, ScopeInterface::SCOPE_STORE)
            ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId
                ]
            )->setTemplateVars($templateVar)
            ->setFromByScope($from)
            ->addTo($toEmail)
            ->getTransport();
        $transport->sendMessage();
        /** @var \Magento\Framework\Mail\EmailMessage $message */
        $message = $transport->getMessage();
        $message->getBodyText();

        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId
        ];

        $template = $this->templateFactory->get($templateId);
        $template->setVars($templateVar)
            ->setOptions($templateOptions);
        $emailBody = $template->processTemplate();

        $blackListModel = $this->blackListRepository->getById(1);
        $blackListModel->setEmailBody($emailBody);
        $this->blackListRepository->saveBlackList($blackListModel);

    }
}
