<?php

class Voga_Sizetables_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);    // This line needs to be added for loading ExtJs
        $this->renderLayout();
    }

    public function categoriesJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('voga_sizetables/adminhtml_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    public function categoriesAction()
    {
        echo $this->getLayout()->createBlock('voga_sizetables/adminhtml_categories')->toHtml();
    }

}
