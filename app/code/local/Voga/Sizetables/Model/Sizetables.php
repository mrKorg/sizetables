<?php

class Voga_Sizetables_Model_Sizetables extends Mage_Core_Model_Abstract
{

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('voga_sizetables/sizetables');
    }

    protected function _getCollection()
    {
        return $this->getCollection();
    }

    /**
     * @param $sizetableId
     * @return array
     */
    public function getSizetablesCategoriesIds($sizetableId)
    {
        $categories = [];
        $collection = $this->_getCollection()->addFieldToFilter('sizetable_id', $sizetableId);
        foreach ($collection as $item){
            $categories[] = $item->getCategoryId();
        }
        return $categories;
    }

}
