<?php

 namespace Amasty\VictoriaModule\Setup;

 use Magento\Backend\Block\Widget\Tab;
 use Magento\Framework\DB\Ddl\Table;
 use Magento\Framework\Setup\InstallSchemaInterface;
 use Magento\Framework\Setup\ModuleContextInterface;
 use Magento\Framework\Setup\SchemaSetupInterface;

 class InstallSchema implements InstallSchemaInterface
 {
     const TABLE_NAME = 'amasty_victoriamodule_blacklist';
     public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
     {
         $setup->startSetup();
         $table = $setup->getConnection()
             ->newTable($setup->getTable(self::TABLE_NAME))
             ->addColumn(
                 'blacklist_id',
                 Table::TYPE_INTEGER,
                 null,
                 [
                     'identity' => true,
                     'nullable' => false,
                     'primary' => true,
                     'unsigned' => true
                 ],
                 'Blacklist Id'
             )
             ->addColumn(
                 'sku',
                 Table::TYPE_TEXT,
                 255,
                 ['nullable' => false],
                 'SKU'
             )
             ->addColumn(
                 'qty',
                 Table::TYPE_INTEGER,
                 10,
                 ['nullable' => false],
                 'QTY'
             )
             ->setComment('Blacklist Table');
         $setup->getConnection()->createTable($table);

         $setup->endSetup();
     }
 }
