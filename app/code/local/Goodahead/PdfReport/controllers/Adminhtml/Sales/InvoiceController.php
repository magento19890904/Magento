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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales orders controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Goodahead_PdfReport_Adminhtml_Sales_InvoiceController extends Mage_Adminhtml_Controller_Action
{
    public function pdfinvoicesAction(){
        $invoicesIds = $this->getRequest()->getPost('invoice_ids');
        if (!empty($invoicesIds)) {
            $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', array('in' => $invoicesIds))
                ->load();
            $reportBlock = $this->getLayout()
                ->createBlock('goodahead_pdfreport/adminhtml_report_pdf');

            $reportBlock->addInvoices($invoices);

            $pdf = Mage::getModel('goodahead_pdfreport/pdf');
            return $this->_prepareDownloadResponse('invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->getPdf($reportBlock), 'application/pdf');
        }
        $this->_redirect('*/*/');
    }
}
