<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Usa
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/* Webshopapps USA Shipping
 *
 * @category   Webshopapps
 * @package    Webshopapps_Usashipping
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license    http://www.webshopapps.com/license/license.txt - Commercial license
 */

class Webshopapps_Wsaupsfreight_Model_Carrier_Wsaupsfreight_Source_Container
{
    public function toOptionArray()
    {
        $fedex = Mage::getSingleton('wsaupsfreight/carrier_wsaupsfreight');
        $arr = array();
        foreach ($fedex->getCode('container') as $k=>$v) {
            $arr[] = array('value'=>$k, 'label'=>$v);
        }
        
        return $arr;
    }
}
