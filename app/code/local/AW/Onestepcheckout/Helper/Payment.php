<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Onestepcheckout
 * @version    1.0.3
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */

class AW_Onestepcheckout_Helper_Payment extends Mage_Core_Helper_Data
{
    /**
     * set shipping method for first load checkout page
     */
    public function initPaymentMethod()
    {
        // check if payment saved to quote
        if (!$this->getQuote()->getPayment()->getMethod()) {
            $data = array();
            $paymentMethods = $this->getPaymentMethods();
            if ((count($paymentMethods) == 1)) {
                $currentPaymentMethod = current($paymentMethods);
                $data['method'] = $currentPaymentMethod->getCode();
            } elseif ($lastPaymentMethod = $this->_getLastPaymentMethod()) {
                $data['method'] = $lastPaymentMethod;
            }
            if (!empty($data)) {
                try {
                    $this->getOnepage()->savePayment($data);
                } catch (Exception $e) {
                    // catch this exception
                }
            }
        }
    }

    public function getPaymentMethods()
    {
        $paymentBlock = Mage::app()->getLayout()->createBlock('aw_onestepcheckout/onestep_form_paymentmethod');
        return $paymentBlock->getMethods();
    }

    protected function _getLastPaymentMethod()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (!$customer->getId()) {
            return false;
        }
        $orderCollection = Mage::getResourceModel('sales/order_collection')
            ->addFilter('customer_id', $customer->getId())
            ->addAttributeToSort('created_at', Varien_Data_Collection_Db::SORT_ORDER_DESC)
            ->setPageSize(1);

        $lastOrder = $orderCollection->getFirstItem();
        if (!$lastOrder->getId()) {
            return false;
        }
        return $lastOrder->getPayment()->getMethod();
    }

    /**
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    public function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }
}