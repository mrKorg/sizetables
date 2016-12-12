<?php
class Voga_Sizetables_Model_Resource_Sizetables_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('voga_sizetables/sizetables');
    }
}
