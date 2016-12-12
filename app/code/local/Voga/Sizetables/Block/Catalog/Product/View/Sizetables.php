<?php

class Voga_Sizetables_Block_Catalog_Product_View_Sizetables extends Mage_Core_Block_Template
{
    public $womanId = 3;
    public $manId = 4;
    public $kidsId = 5;

    /**
     * @return mixed
     */
    public function getCollection(){
        if (is_null($this->_collection)){
            $this->_collection = Mage::getModel('adminforms/block',array('entity_type'=>'voga_sizetables'))->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', RonisBT_AdminForms_Model_Config_Source_Status::STATUS_ENABLED)
                ->setOrder('position', 'ASC')
            ;
            if ($this->getProduct()) {
                $this->_collection->addAttributeToFilter('entity_id', $this->getSizatables());
            }
        }
        return $this->_collection;
    }

    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Return array of product categories
     * @return mixed
     */
    protected function _getProductCategories()
    {
        if (is_null($this->_categories)) {
            $this->_categories = $this->getProduct()->getCategoryIds();
        }
        return $this->_categories;
    }

    /**
     * @return mixed
     */
    public function getSizatables()
    {
        return Mage::getModel('voga_sizetables/sizetables')->getCategorySizetablesArray( $this->_getProductCategories()[0] );
    }

    /**
     * Return parent category second level
     * @return mixed
     */
    public function _getParentCategories($categoryIds)
    {
        $parentCategoriesIds = array();

        foreach ($categoryIds as $categoryId) {
            $level = Mage::getModel('catalog/category')->load($categoryId)->getLevel();
            while ($level > 2) {
                $categoryId = Mage::getModel('catalog/category')->load($categoryId)->getParentId();
                $level = Mage::getModel('catalog/category')->load($categoryId)->getLevel();
            }
            $parentCategoriesIds[] = $categoryId;
        }

        $parentCategoriesIds = array_unique($parentCategoriesIds);

        return $parentCategoriesIds;
    }

    /**
     * @param $parent
     * @return bool
     */
    public function getCollectionByParent($parent)
    {
        $collection = $this->getCollection();
        $womanTables = array();
        $manTables = array();
        $kidsTables = array();

        foreach ($collection as $item) {
            $sizetablesCategoriesIds = Mage::getModel('voga_sizetables/sizetables')->getSizetablesCategoriesIds($item->getEntityId());
            $parentCategoriesIds = $this->_getParentCategories($sizetablesCategoriesIds);
            foreach ($parentCategoriesIds as $parentId) {
                switch ($parentId) {
                    case $this->womanId:
                        $womanTables[] = $item->getEntityId();
                        break;
                    case $this->manId:
                        $manTables[]   = $item->getEntityId();
                        break;
                    case $this->kidsId:
                        $kidsTables[]  = $item->getEntityId();
                        break;
                }
            }
        }

        if ($parent == $this->womanId && $womanTables) {
            return Mage::getModel('adminforms/block',array('entity_type'=>'voga_sizetables'))->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', RonisBT_AdminForms_Model_Config_Source_Status::STATUS_ENABLED)
                ->addAttributeToFilter('entity_id', $womanTables)
                ->setOrder('position', 'ASC');
        } else if ($parent == $this->manId && $manTables) {
            return Mage::getModel('adminforms/block',array('entity_type'=>'voga_sizetables'))->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', RonisBT_AdminForms_Model_Config_Source_Status::STATUS_ENABLED)
                ->addAttributeToFilter('entity_id', $manTables)
                ->setOrder('position', 'ASC');
        } else if ($parent == $this->kidsId && $kidsTables) {
            return Mage::getModel('adminforms/block',array('entity_type'=>'voga_sizetables'))->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', RonisBT_AdminForms_Model_Config_Source_Status::STATUS_ENABLED)
                ->addAttributeToFilter('entity_id', $kidsTables)
                ->setOrder('position', 'ASC');
        }
        return false;
    }


}
