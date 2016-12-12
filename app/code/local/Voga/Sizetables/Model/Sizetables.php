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

    /**
     * @param $sizetableId
     * @return array
     */
    public function getSizetablesCategoriesIds($sizetableId)
    {
        $categories = array();
        $collection = $this->getCollection()->addFieldToFilter('sizetable_id', $sizetableId);
        foreach ($collection as $item){
            $categories[] = $item->getCategoryId();
        }
        return $categories;
    }

    /**
     * @param $categoryIds
     * @return array
     */
    public function getCategorySizetablesArray($categoryIds)
    {
        $sizetables = array();
        $collection = $this->getCollection()->addFieldToFilter('category_id', $categoryIds);

        foreach ($collection as $item){
            $sizetables[] = $item->getSizetableId();
        }
        return $sizetables;
    }

}
