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


class AW_Onestepcheckout_Block_Onestep_Form_Paymentmethod extends Mage_Checkout_Block_Onepage_Payment_Methods
{
    public function getSavePaymentUrl()
    {
        return Mage::getUrl('aw_onestepcheckout/ajax/savePaymentMethod');
    }

    public function getBlockNumber($isIncrementNeeded = true)
    {
        return Mage::helper('aw_onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }

    public function getMethods()
    {
        $methods = $this->getData('methods');
        if (is_null($methods)) {
            $quote = $this->getQuote();
            $store = $quote ? $quote->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getBaseGrandTotal();
            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)
                    && ($total != 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles())
                        || (
                            Mage::getConfig()->getModuleConfig('AW_Sarp2')->is('active')
                            && Mage::helper('aw_sarp2/quote')->isQuoteHasSubscriptionProduct($quote)
                        )
                    )
                ) {
                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }
            $this->setData('methods', $methods);
        }
        return $methods;
    }

    public function getEnterpriseRewardHtml()
    {
        if (Mage::helper('core')->isModuleEnabled('Enterprise_Reward')) {
            return Mage::app()->getLayout()
                ->createBlock('enterprise_reward/checkout_payment_additional')
                ->setTemplate('aw_onestepcheckout/onestep/form/payment/rewards.phtml')
                ->toHtml();
        }
        return '';
    }
}