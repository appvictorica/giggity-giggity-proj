<?php

namespace Amasty\VictoriaModule\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use phpDocumentor\Reflection\Type;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<'))
        {
            $setup->getConnection()->addColumn(
                $setup->getTable(InstallSchema::TABLE_NAME),
                'email_body',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Email body'
                ]
            );
        }

        $setup->endSetup();
    }
}
