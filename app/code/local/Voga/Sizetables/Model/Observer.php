<?php

class Voga_Sizetables_Model_Observer extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('voga_sizetables_tabs');
        $this->setDestElementId('edit_form');
    }

    public function addTab(Varien_Event_Observer $observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $tabs->addTab('categories', array(
            'label'     => Mage::helper('voga_sizetables')->__('Categories'),
            'url'       => $this->getUrl('adminhtml/index/categories', array('_current' => true)),
            'class'     => 'ajax',
        ));
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function saveCatId(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        $categories = $this->getRequest()->getParam('category_ids');

        if (isset($categories) && strlen($categories)){

            // Get selected categories
            $categoriesArray = explode(',', $categories);
            if (!strlen($categoriesArray[0])) {
                array_shift($categoriesArray);
            }
            $categoriesArray = array_unique($categoriesArray);

            // Get old categories
            $write = $block->getResource()->getWriteConnection();
            $oldCategories = Mage::getModel( 'voga_sizetables/sizetables', array('entity_type'=>'sizetables') )
                ->getSizetablesCategoriesIds($block->getId());

            // Difference of id's
            $insert = array_diff($categoriesArray, $oldCategories);
            $delete = array_diff($oldCategories, $categoriesArray);

            // Delete rows
            if (!empty($delete)) {
                $cond = $write->quoteInto('category_id IN(?) AND ', $delete) . $write->quoteInto('sizetable_id=?', $block->getId());
                $write->delete($block->getResource()->getTable('voga_sizetables/table_sizetables'), $cond);
            }

            // Insert/update rows
            if (!empty($insert)) {
                $data = array();
                foreach($insert as $categoryId) {
                    $data[] = array(
                        'sizetable_id' => $block->getId(),
                        'category_id'   => $categoryId
                    );
                }
                $write->insertOnDuplicate($block->getResource()->getTable('voga_sizetables/table_sizetables'), $data, array('sizetable_id', 'category_id'));
            }
        }
    }
}
