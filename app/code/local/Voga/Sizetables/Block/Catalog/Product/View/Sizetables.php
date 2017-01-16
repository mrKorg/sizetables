<?php

class Voga_Sizetables_Block_Catalog_Product_View_Sizetables extends Mage_Core_Block_Template
{
    protected $_womanId = 'voga_sizetables/sizetables/womens_category';
    protected $_manId = 'voga_sizetables/sizetables/mens_category';
    protected $_kidsId = 'voga_sizetables/sizetables/kids_category';

    /**
     * @return mixed
     */
    protected function _getCollection()
    {
        if (is_null($this->_collection)) {

            $this->_collection = Mage::getModel('adminforms/block',array('entity_type'=>'voga_sizetables'))->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', RonisBT_AdminForms_Model_Config_Source_Status::STATUS_ENABLED)
                ->setOrder('position', 'ASC')
            ;
            if ($this->getProduct()) {
                $this->_collection
                    ->getSelect()
                    ->joinLeft(
                        array('sizetables'=> $this->_collection->getTable('voga_sizetables/table_sizetables')), 'sizetables.sizetable_id=e.entity_id',
                        array('entity_sizetable_id' => 'sizetables.entity_id'))
                    ->where('sizetables.category_id IN (?)', $this->_getProductCategories())
                    ->group('sizetables.sizetable_id')
                ;
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
            $productCategories = $this->getProduct()->getCategoryIds();
            $allProductCategories = [];
            foreach ($productCategories as $categoryId) {
                $allProductCategories[] = $categoryId;
                $category = Mage::getModel('catalog/category')->load($categoryId);
                foreach ($category->getParentCategories() as $parent) {
                    $allProductCategories[] = $parent->getId();
                }
            }
        }
        return array_unique($allProductCategories);
    }

    /**
     * Return parent category second level
     * @return mixed
     */
    protected function _getParentCategories($categoryIds)
    {
        $parentCategoriesIds = [];
        $categoryCollection = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('path')
            ->addAttributeToFilter('entity_id', $categoryIds);
        $test = [];
        foreach ($categoryCollection as $category) {
            $categoryId = explode('/', $category->getPath())[2];
            if ( !in_array($categoryId, $parentCategoriesIds) ) {
                $parentCategoriesIds[] = $categoryId;
            }
        }
        return $test;
    }

    /**
     * @param $parent
     * @return bool
     */
    public function getSizetablesCollection()
    {
        $collection = $this->_getCollection();
        $sizetablesCollection = new Varien_Data_Collection();

        foreach ($collection as $item) {
            $sizetablesCategoriesIds = Mage::getModel('voga_sizetables/sizetables')->getSizetablesCategoriesIds($item->getEntityId());
            $parentCategoriesIds = $this->_getParentCategories($sizetablesCategoriesIds);
            foreach ($parentCategoriesIds as $parentId) {
                switch ($parentId) {
                    case Mage::getStoreConfig($this->_womanId):
                        $item->setParent(Mage::getStoreConfig($this->_womanId));
                        break;
                    case Mage::getStoreConfig($this->_manId):
                        $item->setParent(Mage::getStoreConfig($this->_manId));
                        break;
                    case Mage::getStoreConfig($this->_kidsId):
                        $item->setParent(Mage::getStoreConfig($this->_kidsId));
                        break;
                }
            }
            $sizetablesCollection->addItem($item);
        }
        return $sizetablesCollection;
    }

    protected function _isWoman()
    {
        foreach ($this->getSizetablesCollection() as $item)
            if ($item->getParent() == Mage::getStoreConfig($this->_womanId)) return true;
        return false;
    }

    protected function _isMan()
    {
        foreach ($this->getSizetablesCollection() as $item)
            if ($item->getParent() == Mage::getStoreConfig($this->_manId)) return true;
        return false;
    }

    protected function _isKids()
    {
        foreach ($this->getSizetablesCollection() as $item)
            if ($item->getParent() == Mage::getStoreConfig($this->_kidsId)) return true;
        return false;
    }

}
