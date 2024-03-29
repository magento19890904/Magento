<?php

/*
 * @copyright  Copyright (c) 2011 by  ESS-UA.
 */

class Ess_M2ePro_Adminhtml_Template_GeneralController extends Ess_M2ePro_Controller_Adminhtml_MainController
{
    //#############################################

    protected function _initAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('m2epro/templates')
             ->_title(Mage::helper('M2ePro')->__('M2E Pro'))
             ->_title(Mage::helper('M2ePro')->__('Templates'))
             ->_title(Mage::helper('M2ePro')->__('General Templates'));

        $this->getLayout()->getBlock('head')->addJs('M2ePro/Plugin/DropDown.js')
                                            ->addCss('M2ePro/css/Plugin/DropDown.css');

        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('m2epro/templates/general');
    }

    //#############################################

    public function indexAction()
    {
        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('M2ePro/adminhtml_template_general'))
             ->renderLayout();
    }

    public function gridAction()
    {
        $block = $this->loadLayout()->getLayout()->createBlock('M2ePro/adminhtml_template_general_grid');
        $this->getResponse()->setBody($block->toHtml());
    }

    //#############################################

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');

        if (is_null($id)) {
            return $this->_redirect('*/*/index');
        }

        $template = Mage::helper('M2ePro/Component')->getUnknownObject('Template_General', $id);
        return $this->_redirect("*/adminhtml_{$template->getComponentMode()}_template_general/edit", array('id'=>$id));
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $ids = $this->getRequest()->getParam('ids');

        if (is_null($id) && is_null($ids)) {
            $this->_getSession()->addError(Mage::helper('M2ePro')->__('Please select item(s) to remove'));
            return $this->_redirect('*/*/index');
        }

        $idsForDelete = array();
        !is_null($id) && $idsForDelete[] = (int)$id;
        !is_null($ids) && $idsForDelete = array_merge($idsForDelete,(array)$ids);

        $deleted = $locked = 0;
        foreach ($idsForDelete as $id) {
            $template = Mage::helper('M2ePro/Component')->getUnknownObject('Template_General', $id);
            if ($template->isLocked()) {
                $locked++;
            } else {
                $template->deleteInstance();
                $deleted++;
            }
        }

        $tempString = Mage::helper('M2ePro')->__('%count% record(s) were successfully deleted');
        $deleted && $this->_getSession()->addSuccess(str_replace('%count%',$deleted,$tempString));

        $tempString  = Mage::helper('M2ePro')->__('%count% record(s) are used in Listing(s). ');
        $tempString .= Mage::helper('M2ePro')->__('Template must not be in use to be deleted.');
        $locked && $this->_getSession()->addError(str_replace('%count%',$locked,$tempString));

        $this->_redirect('*/*/index');
    }

    //#############################################
}