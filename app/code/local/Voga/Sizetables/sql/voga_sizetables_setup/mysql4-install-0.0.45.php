<?php

/** @var Mage_Core_Model_Resource_Setup $installer */

$installer = $this;
$tableSizes = $installer->getTable('voga_sizetables/table_sizetables');

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($tableSizes)
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary'  => true
    ))
    ->addColumn('sizetable_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null)
    ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null)
    ->addForeignKey(
        $installer->getFkName('voga_sizetables/table_sizetables', 'category_id', 'catalog/category', 'entity_id'),
        'category_id',
        $installer->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );

$installer->getConnection()->createTable($table);
$installer->endSetup();
