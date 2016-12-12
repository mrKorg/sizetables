<?php

class Voga_Sizetables_Model_Resource_Sizetables extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('voga_sizetables/table_sizetables', 'entity_id');
    }

}
