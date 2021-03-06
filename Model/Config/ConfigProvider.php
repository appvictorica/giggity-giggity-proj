<?php

namespace Amasty\VictoriaModule\Model\Config;

use Symfony\Component\Console\Input\StringInput;

class ConfigProvider extends ConfigProviderAbstract
{
    const QTY_GROUP = 'qty_general/';
    const GENERAL_GROUP = 'general/';
    const TEMPLATE_GROUP = 'template_general/';

    const IS_ENABLED = 'module_enabled';
    const GREETING_TEXT = 'greeting_text';
    const IS_SHOW_QTY_FIELD = 'qty_hide';
    const QTY_DEFAULT = 'qty_default';
    const EMAIL_TEXT = 'email_to';
    const TEMPLATE_SELECT = 'select_template';

    protected $pathPrefix = 'victoriamod_config/';

    /**
     * @param int $storeId
     */
    public function getIsEnabled($storeId)
    {
       return $this->isSetFlag(self::GENERAL_GROUP . self::IS_ENABLED, $storeId);
    }

    /**
     * @param int $storeId
     */
    public function getIsShowQtyField($storeId)
    {
        return $this->isSetFlag(self::QTY_GROUP.self::IS_SHOW_QTY_FIELD, $storeId);
    }

    /**
     * @param int $storeId
     */
    public function getGreetingText($storeId)
    {
       return $this->getValue(self::GENERAL_GROUP.self::GREETING_TEXT, $storeId);
    }

    /**
     * @param $storeId
     */
    public function getQtyDefault($storeId)
    {
        return $this->getValue(self::QTY_GROUP.self::QTY_DEFAULT, $storeId);
    }

    /**
     * @param $storeId
     */
    public function getEmailTo($storeId)
    {
        return $this->getValue(self::TEMPLATE_GROUP.self::EMAIL_TEXT, $storeId);
    }

    /**
     * @param $storeId
     */
    public function getTemplateName($storeId)
    {
        return $this->getValue(self::TEMPLATE_GROUP.self::TEMPLATE_SELECT, $storeId);
    }
}
