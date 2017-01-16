<?php

class Voga_Sizetables_Model_System_Config_Source_Categories
{
    protected $_categories = Null;

    public function toOptionArray()
    {
        $categories = $this->_getCategoriesCollection();
        $options = array();

        foreach ($categories as $id => $category) {
            if (isset($category['name']) && isset($category['level'])) {
                if ($category['level'] < 1) {
                    $category['level'] = 1;
                }
                $options[] = array(
                    'value' => $id,
                    'label' => str_repeat("-", $category['level'] - 1) . ' ' . addslashes($category['name']),
                    //'style' => 'padding-left:' . (($category['level'] - 1) * 7) . 'px;',
                );
            }
        }
        return $options;
    }

    protected function _getCategoriesCollection()
    {
        if (is_null($this->_categories)) {
            $collection = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('entity_id')
                ->addAttributeToSort('path', 'asc')
                //~ ->addAttributeToFilter('is_active', 1)
                ->addAttributeToFilter('name', array('neq' => ''))
                ->addAttributeToFilter('level', array('gteq' => 1))
                ->load();
            $this->_categories = $collection->toArray();
        }
        return $this->_categories;
    }
}
